<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'controllers/EstabelecimentoController.php';
require_once 'controllers/LogExecutionController.php';
require_once 'controllers/LogUpdateController.php';
require_once 'controllers/VersionsController.php';
require_once 'controllers/ValidationController.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (isset($data['method']) && isset($data['data'])) {
    $method = $data['method'];
    $requestData = $data['data'];

    try {
        switch ($method) {
            // Métodos para EstabelecimentoController
            case 'getEstabelecimentos':
                $response = EstabelecimentoController::getEstabelecimentos();
                break;
            case 'getEstabelecimentoByCnpj':
                $response = EstabelecimentoController::getEstabelecimentoByCnpj($requestData['cnpj'], $requestData['hash']);
                break;
            case 'createEstabelecimento':
                $response = EstabelecimentoController::createEstabelecimento($requestData);
                break;
            case 'updateEstabelecimento':
                $response = EstabelecimentoController::updateEstabelecimento($requestData['id'], $requestData);
                break;
            case 'deleteEstabelecimento':
                $response = EstabelecimentoController::deleteEstabelecimento($requestData['id']);
                break;

           // Métodos para LogExecutionController
            case 'getLogExecutions':
                $response = LogExecutionController::getLogExecutions();
                break;
            case 'getLogExecutionById':
                $response = LogExecutionController::getLogExecutionById($requestData['id']);
                break;
            case 'createLogExecution':
                $response = LogExecutionController::createLogExecution($requestData);
                break;
            case 'updateLogExecution':
                $response = LogExecutionController::updateLogExecution($requestData['id'], $requestData);
                break;
            case 'deleteLogExecution':
                $response = LogExecutionController::deleteLogExecution($requestData['id']);
                break;

            // Métodos para LogUpdateController
            case 'getLogUpdates':
                $response = LogUpdateController::getLogUpdates();
                break;
            case 'getLogUpdateById':
                $response = LogUpdateController::getLogUpdateById($requestData['id']);
                break;
            case 'createLogUpdate':
                $response = LogUpdateController::createLogUpdate($requestData);
                break;
            case 'updateLogUpdate':
                $response = LogUpdateController::updateLogUpdate($requestData['id'], $requestData);
                break;
            case 'deleteLogUpdate':
                $response = LogUpdateController::deleteLogUpdate($requestData['id']);
                break;

            // Métodos para VersionsController
            case 'getVersions':
                $response = VersionsController::getVersions();
                break;
            case 'getVersionById':
                $response = VersionsController::getVersionById($requestData['version']);
                break;
            case 'createVersion':
                $response = VersionsController::createVersion($requestData);
                break;
            case 'updateVersion':
                $response = VersionsController::updateVersion($requestData['id'], $requestData);
                break;
            case 'deleteVersion':
                $response = VersionsController::deleteVersion($requestData['id']);
                break;

            // Método para ValidationController
            case 'validateEstabelecimento':
                $response = ValidationController::validateEstabelecimento($requestData['cnpj'], $requestData['hash']);
                break;

            default:
                http_response_code(405);
                $response = array('error' => 'Método não suportado');
                break;

            
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    } catch (Exception $e) {
        http_response_code(500);
        $response = array('error' => 'Erro interno do servidor: ' . $e->getMessage());
        echo json_encode($response);
    }
} else {
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode(array('error' => 'Parâmetros inválidos'));
}
?>
