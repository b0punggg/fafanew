<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <script>
    fetch("http://localhost:3000/print/qr-grid", {
  method: "POST",
  headers: { "Content-Type": "application/json" },
  body: JSON.stringify({
    title: "LABEL BARANG",
    data: [
      { "qr": "https://example.com/product1", "text": "Produk A", "price": "Rp10.000" },
    { "qr": "https://example.com/product2", "text": "Produk B", "price": "Rp20.000" },
    { "qr": "https://example.com/product3", "text": "Produk C", "price": "Rp15.000" },
    { "qr": "https://example.com/product4", "text": "Produk D", "price": "Rp12.500" }
    ],
    footer: "Selesai"
  })
});

</script>


  
</body>
</html>