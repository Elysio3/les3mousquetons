<?php

/**
 * Get data from the API
 * @param mixed $table
 * @param mixed $data
 * @return mixed
 */
function getData($table, $data = null) {

    $api_base_url = "https://3m.alysia.fr/api/";
    $ch = curl_init();

    if ($data != null) {
        if (isset($data['id'])) {
            $id = $data['id'];
            curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table . "&id=" . $id);

            // specific case for route_status table
        } elseif (isset($data['user_id']) && isset($data['route_id'])) {
            $user_id = $data['user_id'];
            $route_id = $data['route_id'];

            curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table . "&user_id=" . $user_id . "&route_id=" . $route_id);
        } else {
            curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table);
        }
    } else {
        curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table);
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $headers = [
        'Authorization: ' . $_ENV['READ_ONLY_HASHED'],
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch) || $response == null) {
        echo 'Error:' . curl_error($ch);
        return [];
    } else {
        return json_decode($response, true);
    }
}

/**
 * Post data to the API
 * @param mixed $table
 * @param mixed $data
 * @return mixed
 */
function postData($table, $data) {
    $api_base_url = "https://3m.alysia.fr/api/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    $headers = [
        'Authorization: ' . $_ENV['WRITE_HASHED'],
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch) || $response == null) {
        echo 'Error:' . curl_error($ch);
        return false;
    } else {
        return json_decode($response, true);
    }
}

/**
 * Put data to the API
 * @param mixed $table
 * @param mixed $data
 * @return mixed
 */
function putData($table, $data) {
    $api_base_url = "https://3m.alysia.fr/api/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    $headers = [
        'Authorization: ' . $_ENV['WRITE_HASHED'],
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch) || $response == null) {
        echo 'Error:' . curl_error($ch);
        return false;
    } else {
        return json_decode($response, true);
    }
}

/**
 * Delete data from the API
 * @param mixed $table
 * @param mixed $id
 * @return mixed
 */
function deleteData($table, $id) {
    $api_base_url = "https://3m.alysia.fr/api/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_base_url . "?table=" . $table . "&id=" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    $headers = [
        'Authorization: ' . $_ENV['WRITE_HASHED'],
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    if (curl_errno($ch) || $response == null) {
        echo 'Error:' . curl_error($ch);
        return false;
    } else {
        return json_decode($response, true);
    }
}

