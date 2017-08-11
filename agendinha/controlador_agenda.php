<?php
        //cadastra usuário
function cadastrar($nome,$email,$telefone){
            //Pega o arquivo contatos.json, decodifica e me devolve os contatos
    $contatosAuxiliar = pegaContatos();
            //A variável $contato recebe os parâmetros enviados atraveś do formulário
    $contato = [
        'id'      => uniqid(),
        'nome'    => $nome,
        'email'   => $email,
        'telefone'=> $telefone
    ];
            //Pega o contato e coloca na contatosAuxiliar
    array_push($contatosAuxiliar, $contato);
                //E atualiza o arquivo;
    atualizarArquivo($contatosAuxiliar);
}

                //Função pegarContatos pega todos os contatos do arquivo contatos.json
function pegaContatos($valor_buscado = null){

    if ($valor_buscado == null){
    //Pega o arquivo
        $contatosAuxiliar = file_get_contents('contatos.json');
 //decodifica
        $contatosAuxiliar = json_decode($contatosAuxiliar, true);
//retorna o arquivo
        return $contatosAuxiliar;
    } else {
        return buscarContato($valor_buscado);
    }
}

        //Exclui os contatos
function excluirContato($id){
            //Pegar os contatos
    $contatosAuxiliar = pegaContatos();
            // Pra cada contatoAuxiliar que temos, chamo de contato e pego a posição
    foreach ($contatosAuxiliar as $posicao => $contato){
                //Se o Id é o mesmoq que procuramos
        if($id == $contato['id']) {
                    //exclui os dados do contato pelo id
            unset($contatosAuxiliar[$posicao]);
        }
    }

    atualizarArquivo($contatosAuxiliar);
}
                //Função para editar o contato
function editarContato($id){
               //Pego os contatos
    $contatosAuxiliar = pegaContatos();
            //Pra cada contatoAuxiliar como contato
    foreach ($contatosAuxiliar as $contato){
     //Se e o id que procuro e o id do contato são os mesmos
        if ($contato['id'] == $id){
     //retorna o contato com seus dados
            return $contato;
        }
    }
}
//Salva contato editado
function salvarContatoEditado($id){
    //Pego os contatos
    $contatosAuxiliar = pegaContatos();
    //Para cada contatoAuxiliar como a posição do contato
    foreach ($contatosAuxiliar as $posicao => $contato){
    //Se o id do contato é o id que procuramos
        if ($contato['id'] == $id){
        //Então posso editar o contato
            $contatosAuxiliar[$posicao]['nome'] = $_POST['nome'];
            $contatosAuxiliar[$posicao]['email'] = $_POST['email'];
            $contatosAuxiliar[$posicao]['telefone'] = $_POST['telefone'];
            break;
        }
    }
    //Atualiza o arquivo
    atualizarArquivo($contatosAuxiliar);
}
//Atualizar o arquivo;
function atualizarArquivo($contatosAuxiliar){
    //Depois de alterar a lista, o arquivo é decodificado
    $contatosJson = json_encode($contatosAuxiliar, JSON_PRETTY_PRINT);
    //recbe todos os dados e substitui todos
    file_put_contents('contatos.json', $contatosJson);
    //me manda pra página inicial
    header("Location: index.phtml");
}
//buscar contato pelo nome;
function buscarContato($nome){
    //Pega os contatos
    $contatosAuxiliar = pegaContatos();

    $contatosEncontrados = [];

    //Pra cada contatoAuxiliar como contato
    foreach ($contatosAuxiliar as $contato){
        //Se e o id do contato é o mesmo que a gente procura
        if ($contato['nome'] == $nome){
            //retorne o contato com seus dados
            $contatosEncontrados[] = $contato;
        }
    }

    return $contatosEncontrados;
}
//ROTAS
switch($_GET['acao']){
    case "cadastrar":
    cadastrar($_POST['nome'],$_POST['email'],$_POST['telefone']);
        break;
    case "editar":
        salvarContatoEditado($_POST['id']);
        break;
    case "excluir":
        excluirContato($_GET['id']);
        break;
}