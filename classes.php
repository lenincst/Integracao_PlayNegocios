<?php


class PlayNegocios
{

    // Método para processar o email
    public function PegaEmailUrl($email)
    {
        if (isset($_GET['email'])) {
            // Obtém o valor do parâmetro 'email'
            $email = $_GET['email'];
            // Transforma o email em minúsculas e remove espaços em branco extras
            $email = strtolower(trim($email));
            // Retorna o email processado
            return $email;
        } else {
            // Se não houver email, imprime uma mensagem de erro e retorna um valor nulo
            echo "Erro: Nenhum email fornecido.";
            return null;
        }
    }

    // Método para listar os emails
    public function buscarEmail($email)
    {
        include '/xampp/htdocs/Play_Negocios/authentication/authentication.php';
        $token = $config['token'];
        $url_buscarEmail = "https://crm.rdstation.com/api/v1/contacts?token={$token}&email={$email}";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url_buscarEmail,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'content-type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        // Decodifica a resposta JSON
        $data = json_decode($response, true);
        // Verifica se existe pelo menos um contato
        $email_verdadeiro = ($data['total'] > 0);
        // Inicializa a variável para armazenar o ID (se existir)
        $contato_id = null;
        // Obtém o ID do primeiro contato, se existir
        if ($email_verdadeiro) {
            $contato_id = $data['contacts'][0]['id'];
        } else {
            $contato_id = null;
        }

        // Exibe o resultado
        if ($email_verdadeiro) {
            echo "O e-mail existe. ID do contato: $contato_id\n";
        } else {
            echo "O e-mail não existe.\n";
        }
    }
    // Criar Negociação
    public function criarNegociacao($contato_id)
    {
        include '/xampp/htdocs/Play_Negocios/authentication/authentication.php';
        $token = $config['token'];
        if ($contato_id != null) {

            $url_criar_negociacao = "https://crm.rdstation.com/api/v1/contacts/{$contato_id}?token={$token}";

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url_criar_negociacao,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'content-type: application/json'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            // Decodificar a resposta JSON
            $data = json_decode($response, true);

            // Verificar se a decodificação foi bem-sucedida
            if ($data !== null) {
                // Obter a lista de deal_ids
                $dealIds = $data['deal_ids'];

                // Verificar se há pelo menos um deal_id na lista
                if (!empty($dealIds)) {
                    // Obter o último elemento da lista
                    $ultimoDealId = end($dealIds);

                    // Exibir o resultado
                    echo "O último deal_id é: $ultimoDealId";
                } else {
                    echo "A lista deal_ids está vazia.";
                }
            } else {
                echo "Falha ao decodificar a resposta JSON.";
            }
        }
    }
}
