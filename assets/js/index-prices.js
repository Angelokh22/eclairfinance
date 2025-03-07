function truncateNumber(num, precision) {
  const numStr = num.toString();
  if (numStr.includes(".")) {
    const [integerPart, decimalPart] = numStr.split(".");
    // Format the integer part with commas
    const formattedInteger = Number(integerPart).toLocaleString();
    // Truncate the decimal part to the specified precision
    const truncatedDecimal = decimalPart.slice(0, precision);
    return formattedInteger + "." + truncatedDecimal;
  }
  // If there's no decimal part, just format the integer part
  return Number(numStr).toLocaleString();
}

function getPrice(coin, destination, last_price) {
  setInterval(async () => {
    await fetch(`https://api.coinbase.com/v2/prices/${coin}-USD/buy`)
      .then((response) => response.json())
      .then((data) => {
        const price = truncateNumber(Number(data.data.amount), 5);
        const td = document.getElementsByClassName(destination)[0];
        td.innerHTML = `$${price}`;

        var last = document.getElementsByClassName(last_price)[0];
        if (price > last.innerHTML) {
          td.classList.remove("has-text-danger");
          td.classList.add("has-text-success");
          last.innerHTML = price;
        } else if (price < last.innerHTML) {
          td.classList.remove("has-text-success");
          td.classList.add("has-text-danger");
          last.innerHTML = price;
        }
      });
  }, 500);
}

// getPrice("BTC", "btc-price", "btc-last");
// getPrice("ETH", "eth-price", "eth-last");
// getPrice("BNB", "bnb-price", "bnb-last");
// getPrice("XRP", "xrp-price", "xrp-last");
// getPrice("DOGE", "doge-price", "doge-last");
// getPrice("TRX", "trx-price", "trx-last");
