<?php
header('Content-Type: application/json');

// Get POST data
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$description = isset($_POST['description']) ? $_POST['description'] : 'Order Payment';
$remarks = isset($_POST['remarks']) ? $_POST['remarks'] : '';

// PayMongo expects amount in centavos (PHP * 100)
$amount_cents = intval($amount * 100);

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.paymongo.com/v1/links",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'data' => [
        'attributes' => [
            'amount' => $amount_cents,
            'description' => $description,
            'remarks' => $remarks
        ]
    ]
  ]),
  CURLOPT_HTTPHEADER => [
    "accept: application/json",
    "authorization: Basic c2tfdGVzdF96aXVZUHNNWDU5cjVlREJwQTFmYXYxTmQ6",
    "content-type: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo json_encode(['success' => false, 'error' => $err]);
} else {
  $body = json_decode($response, true);
  $checkout_url = $body['data']['attributes']['checkout_url'] ?? null;
  if ($checkout_url) {
    echo json_encode(['success' => true, 'url' => $checkout_url]);
  } else {
    echo json_encode(['success' => false, 'error' => $response]);
  }
}