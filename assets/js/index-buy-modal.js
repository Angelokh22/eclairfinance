document
  .getElementById("cryptoChoice")
  .addEventListener("change", function (event) {
    // console.log("Selected company: " + event.target.value);
    showBuyQuantity(event.target.value, "buy_quantity");
  });

function buyCrypto(e) {
  const selectElement = document.getElementById("cryptoChoice");

  if (e != 0) {
    const parent = e.parentElement.parentElement;
    const choice = parent.querySelector("img").getAttribute("alt");

    for (let option of selectElement.options) {
      if (option.value == choice) {
        option.selected = true;
        showBuyQuantity(choice, "buy_quantity");
        break;
      }
    }
  } else {
    selectElement.value = e;
    // showBuyQuantity(0, "buy_quantity");
    document.getElementById("buy_quantity").value = "Quantity";
  }

  $("#buy-crypto-modal").addClass("is-active");
}

function showBuyQuantity(id, e) {
  var element = document.getElementById(e);
  var share = document.getElementById(e);

  if (id != 0) {
    fetch(`./pages/api/getCompanySharePrice.php?companyid=${id}`, {})
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          price = response.sharePrice;

          element.value = "1 share = $" + price;
          share.setAttribute("setshare", price);
        }
      });
  } else {
    element.value = "Quantity";
    share.setAttribute("setshare", 0);
  }
}

function updatePrice() {
  var priceInput = document.getElementById("priceInput").value;
  if (priceInput != "" || priceInput != "0") {
    SharePrice = document
      .getElementById("buy_quantity")
      .getAttribute("setshare");

    var quantity = priceInput / SharePrice;

    document.getElementById("buy_quantity").value = quantity;
  }
}

function buyShare(userid) {
  var companyid = document.getElementById("cryptoChoice").value;
  var amount = document.getElementById("priceInput").value;

  fetch(
    `./pages/api/player/buy_share.php?userid=${userid}&companyid=${companyid}&amount=${amount}`,
    {}
  )
    .then((response) => response.json())
    .then((response) => {
      var notif = document.getElementById("notification-container");

      if (response.success) {
        notif.innerHTML = "Transaction Completed!";
        notif.classList.add("is-success");
        notif.classList.remove("is-hidden");

        setTimeout(() => {
          notif.classList.add("is-hidden");
        }, 3000);
      } else {
        notif.innerHTML = response.error;
        notif.classList.add("is-danger");
        notif.classList.remove("is-hidden");

        setTimeout(() => {
          notif.classList.add("is-hidden");
        }, 3000);
      }
    });
}
