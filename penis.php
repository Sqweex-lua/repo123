<?php
// Ссылка на ваш RAW JSON на GitHub
$github_url = "https://raw.githubusercontent.com/Sqweex-lua/repo123/main/keys.json";
$keys_data = json_decode(file_get_contents($github_url), true);

$user_key = $_GET['key'];
$user_hwid = $_GET['hwid'];

$response = ["status" => "error", "message" => "Key not found"];

foreach ($keys_data['keys'] as $k) {
    if ($k['key'] === $user_key) {
        // Проверка срока годности
        if (strtotime($k['expiry']) < time()) {
            $response = ["status" => "expired", "message" => "Key expired"];
        } 
        // Привязка HWID (если в JSON hwid пустой - привязываем при первом входе)
        else if ($k['hwid'] !== "" && $k['hwid'] !== $user_hwid) {
            $response = ["status" => "hwid_mismatch", "message" => "Invalid Device"];
        } 
        else {
            $response = ["status" => "success", "expiry" => $k['expiry']];
        }
        break;
    }
}

echo json_encode($response);
?>
