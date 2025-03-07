uid = "";

document
  .getElementById("shareSendChoice")
  .addEventListener("change", function () {
    sendShares(uid);
  });

function sendShares(userid) {
  uid = userid;
  const selectElement = document.getElementById("shareSendChoice");
  var companyid = selectElement.value;
  var sellAvailable = document.getElementById("sendAvailable");
  var amountInput = document.getElementById("priceInput-send");

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

function updateSendingPrice() {
  var priceInput = document.getElementById("priceInput-send");
  var sellAvailable = document.getElementById("sendAvailable");
}

function SubmitSend() {
  var amount = document.getElementById("priceInput-send").value;
  const selectElement = document.getElementById("shareSendChoice");
  var companyid = selectElement.value;
  const transfer_to = document.getElementById("transfer-to");
  var to = transfer_to.value;

  if (to != 0) {
    let formData = new FormData();
    formData.append("userid", uid);
    formData.append("companyid", companyid);
    formData.append("amount", amount);
    formData.append("to", to);
    fetch("./pages/api/player/send_shares.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((response) => {
        var notif = document.getElementById("notification-container");

        if (response.success) {
          notif.innerHTML = "Transfer Completed!";
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
}
