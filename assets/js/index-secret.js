function redeemSecret(userid) {
  var secretCode = document.getElementById("secretCodeInput");

  let formData = new FormData();
  formData.append("userid", userid);
  formData.append("code", secretCode.value);
  fetch("./pages/api/player/redeemSecret.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((response) => {
      var notif = document.getElementById("notification-container");

      if (response.success) {
        notif.innerHTML = "NFT Redeemed!";
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
  secretCode.value = "";
}
