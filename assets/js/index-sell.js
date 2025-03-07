var uid = "";

document
  .getElementById("shareSellChoice")
  .addEventListener("change", function () {
    sellShares(uid);
  });

function sellShares(userid) {
  uid = userid;
  const selectElement = document.getElementById("shareSellChoice");
  var companyid = selectElement.value;
  var sellAvailable = document.getElementById("sellAvailable");
  var amountInput = document.getElementById("priceInput-sell");

  let formData = new FormData();
  formData.append("userid", userid);
  formData.append("companyid", companyid);

  fetch("./pages/api/player/available_shares.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((response) => {
      if (response.success) {
        sellAvailable.value = response.availableShares;
        amountInput.removeAttribute("disabled");
      } else {
        sellAvailable.value = "Available";
        amountInput.setAttribute("disabled", "");
      }
    });

  fetch(`./pages/api/getCompanySharePrice.php?companyid=${companyid}`)
    .then((response) => response.json())
    .then((response) => {
      if (response.success) {
        var price = response.sharePrice;

        sellAvailable.setAttribute("original", price);
      }
    });
}

function updateSellingPrice() {
  var priceInput = document.getElementById("priceInput-sell");
  var sellAvailable = document.getElementById("sellAvailable");
  var priceSellOutput = document.getElementById("priceSellOutput");

  priceSellOutput.value =
    "$ " + priceInput.value * sellAvailable.getAttribute("original");
}

function SubmitSell() {
  var amount = document.getElementById("priceInput-sell").value;
  const selectElement = document.getElementById("shareSellChoice");
  var companyid = selectElement.value;

  let formData = new FormData();
  formData.append("userid", uid);
  formData.append("companyid", companyid);
  formData.append("amount", amount);
  fetch("./pages/api/player/sell_share.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((response) => {
      var notif = document.getElementById("notification-container");

      if (response.success) {
        notif.innerHTML = "Transaction Completed!";
        notif.classList.remove("is-danger");
        notif.classList.add("is-success");
        notif.classList.remove("is-hidden");

        setTimeout(() => {
          notif.classList.add("is-hidden");
        }, 3000);
      } else {
        notif.innerHTML = response.error;
        notif.classList.remove("is-success");
        notif.classList.add("is-danger");
        notif.classList.remove("is-hidden");

        setTimeout(() => {
          notif.classList.add("is-hidden");
        }, 3000);
      }
    });
}
