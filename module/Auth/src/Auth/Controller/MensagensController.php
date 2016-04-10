<?php

namespace Auth\Controller;

use Core\Controller\ActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\Mensagens as Form;

class MensagensController extends ActionController {

    private $totais;

    function getTotais() {
        return $this->totais;
    }

    function setTotais() {
        $usuario = $this->getUser();
        $id_usuario = $usuario->id;
        $nome = $usuario->nome;
        $mensagens_enviadas = $this->getService('Auth\Service\Mensagens')->getMensagensEnviadas($nome);
        $mensagens_nao_lidas = $this->getService('Auth\Service\Mensagens')->getMensagensNaoLidas($id_usuario);
        $mensagens_lidas = $this->getService('Auth\Service\Mensagens')->getMensagensLidas($id_usuario);
        $mensagens_favoritas = $this->getService('Auth\Service\Mensagens')->getMensagensFavoritas($id_usuario);
        $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagens($id_usuario);
        $qtd_en = count($mensagens_enviadas);
        $qtd_nl = count($mensagens_nao_lidas);
        $qtd_li = count($mensagens_lidas);
        $qtd_es = count($mensagens_favoritas);
        $qtd_tot = count($mensagens);
        $totais = array('nao_lidas' => $qtd_nl, 'lidas' => $qtd_li, 'enviadas' => $qtd_en, 'estrelas' => $qtd_es, 'todas' => $qtd_tot);
        $this->totais = $totais;
    }

    public function indexAction() {
        $usuario = $this->getUser();
        $id_usuario = $usuario->id;
        $nome = $usuario->nome;
        $session = $this->getService('Session');
        $this->setTotais();
        $enviadas = '';
        if ($this->params()->fromRoute('sent', 0) > 0) {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagensEnviadas($nome);
            $enviadas = '1';
        } else if ($this->params()->fromRoute('unread', 0) > 0) {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagensNaoLidas($id_usuario);
        } else if ($this->params()->fromRoute('read', 0) > 0) {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagensLidas($id_usuario);
        } else if ($this->params()->fromRoute('starred', 0) > 0) {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagensFavoritas($id_usuario);
        } else if ($this->params()->fromRoute('ajax', 0) > 0) {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagensNaoLidas($id_usuario);
            $session->offsetUnset('mensagens');
            $session->offsetSet('mensagens', $mensagens);
            exit;
        } else {
            $mensagens = $this->getService('Auth\Service\Mensagens')->getMensagens($id_usuario);
        }
        $jsonObject = json_encode($mensagens);
        $session->offsetSet('json', $jsonObject);
        $session->offsetSet('aside', 'left');
        return new ViewModel(array(
            'json' => $mensagens,
            'nome' => $nome,
            'totais' => $this->getTotais(),
            'enviadas' => $enviadas,
        ));
    }

    public function marcarAction() {
        $opt = $this->params()->fromRoute('opt', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost();
            try {
                if ($opt == 1) {
                    $this->getService('Auth\Service\Mensagens')->marcarLido($values['ids']);
                } else if ($opt == 2) {
                    $this->getService('Auth\Service\Mensagens')->marcarNaoLido($values['ids']);
                } else if ($opt == 3) {
                    $this->getService('Auth\Service\Mensagens')->marcaFavorito($values['ids']);
                } else if ($opt == 4) {
                    $this->getService('Auth\Service\Mensagens')->desmarcaFavorito($values['ids']);
                } else if ($opt == 5) {
                    $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $values['id']);
                    if ($mensagem->favorito == 1) {
                        $this->getService('Auth\Service\Mensagens')->desmarcaFavorito($values);
                    } else {
                        $this->getService('Auth\Service\Mensagens')->marcaFavorito($values);
                    }
                }
                echo 'true';
            } catch (Exception $ex) {
                echo 'false';
            }
            exit;
        }
    }

    public function mensagemAction() {
        $id = $this->params()->fromRoute('id', 0);
        $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
        $anexos = $this->getObjectManager()->getRepository('Auth\Model\AnexoMensagem')->findBy(array('mensagem' => $mensagem));
        if (!$mensagem) {
            $this->flashMessenger()->addErrorMessage('Mensagem não encontrada');
            return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
        }
        $solicitacao = null;
        if ($mensagem->tipo_mensagem == 'Solicitação' && $mensagem->solicitacao != null) {
            $solicitacao = 1;
        }
        if ($this->params()->fromRoute('marcar', 0) == 1) {
            $this->getService('Auth\Service\Mensagens')->marcarLido($mensagem);
            return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
        }
        $usuario = $this->getUser();
        try {
            $this->getService('Auth\Service\Mensagens')->marcarLidoMsg($mensagem);
        } catch (Exception $ex) {
            $this->flashMessenger()->addErrorMessage('Não foi possivel visualizar a mensagem');
            return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
        }
        $session = $this->getService('Session');
        $session->offsetSet('aside', 'left');
        $this->setTotais();
        return new ViewModel(array(
            'id' => $id,
            'mensagem' => $mensagem,
            'assunto' => $mensagem->assunto,
            'nome' => $usuario->nome,
            'solicitacao' => $solicitacao,
            'totais' => $this->getTotais(),
            'anexos' => $anexos,
        ));
    }

    public function deleteAction() {
        $id = $this->params()->fromRoute('id', 0);
        $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
        try {
            $this->getService('Auth\Service\Mensagens')->deletarMensagem($mensagem);
            $this->flashMessenger()->addSuccessMessage('Mensagem deletada com sucesso!');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Não foi possivel deletar a mensagem!');
            return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
        }
        return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
    }

    public function apagarAction() {
        $enviadas = $this->params()->fromRoute('enviadas', 0);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost();
            try {
                foreach ($values['mensagens'] as $mensagem) {
                    $mens = $this->getObjectManager()->find('Auth\Model\Mensagens', $mensagem);
                    if ($enviadas > 0) {
                        $this->getService('Auth\Service\Mensagens')->esquecerEnviada($mens);
                    } else {
                        $anexos = $this->getObjectManager()->getRepository('Auth\Model\AnexoMensagem')->findBy(array('mensagem' => $mens));
                        $this->getService('Auth\Service\Mensagens')->deletarAnexo($anexos);
                        $this->getService('Auth\Service\Mensagens')->deletarMensagem($mens);
                    }
                }
                echo 'true';
            } catch (\Exception $e) {
                echo 'false';
            }
            exit;
        }
    }

    public function saveAction() {
        $usuarios = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findAll();
        $form = new Form($usuarios);
        $request = $this->getRequest();
        $autor = $this->getUser();
        $session = $this->getService('Session');
        $session->offsetSet('aside', 'left');
        $this->setTotais();
        if ($request->isPost()) {
            $values = $request->getPost();
            $usuarios = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findBy(array('nome' => explode(',', $values['tags'])));
            $form->setInputFilter($form->getFilters());
            $form->setData($values);
            $post = array_merge_recursive(
                    $request->getPost()->toArray(), $request->getFiles()->toArray()
            );
            if ($form->isValid()) {
                try {
                    $this->getService('Auth\Service\Mensagens')->newMessage($usuarios, $values, $autor->nome, $post['anexo']);
                    $this->flashMessenger()->addSuccessMessage('Mensagem enviada com sucesso!');
                } catch (Exception $ex) {
                    $this->flashMessenger()->addErrorMessage('Não foi possivel enviar a mensagem!');
                    return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
                }
                return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
            } else {
                echo $form->getMessages();
            }
        }
        return new ViewModel(array(
            'form' => $form,
            'totais' => $this->getTotais(),
        ));
    }

    public function respostaAction() {
        $id = $this->params()->fromRoute('id', 0);
        $mensagem_original = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
        $autor = $this->getUser();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $value = $request->getPost();
            $resposta = strip_tags($value['resposta']);
            try {
                $answer = $this->getService('Auth\Service\Mensagens')->resposta($mensagem_original, $resposta, $autor->nome);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
            echo $answer;
            exit;
        }
    }

    public function autorizacaoAction() {
        $id = $this->params()->fromRoute('id', 0);
        $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $values = $request->getPost();
            $autorizacao = $this->getObjectManager()->getRepository('Auth\Model\AutorizacaoMensagens')->findOneBy(array('id' => $mensagem->solicitacao));
            $mensagens = $this->getObjectManager()->getRepository('Auth\Model\Mensagens')->findBy(array('solicitacao' => $autorizacao));
            $autorizacao->pass = (int) $values['pass'];
            if ($values['pass'] == 1) {
                $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('nome' => $mensagem->autor));
                $autor = $this->getUser();
                $subject = "Permissão concedida";
                $msg = "Seu pedido de autorização foi aprovado. \n ---- \n " . $mensagem->mensagem;
                $type = "Notificação";
            } else {
                $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('nome' => $mensagem->autor));
                $autor = $this->getUser();
                $subject = "Permissão negada";
                $msg = "Seu pedido de autorização foi negado. \n ---- \n " . $mensagem->mensagem;
                $type = "Notificação";
            }
            $this->getService('Auth\Service\Mensagens')->saveMensagem($usuario->id, $msg, $subject, $type, $autor->nome);
            foreach ($mensagens as $msg) {
                if ($msg->id != $id) {
                    $this->getService('Auth\Service\Mensagens')->deletarMensagem($msg);
                }
            }
            $this->getObjectManager()->persist($autorizacao);
            try {
                $this->getObjectManager()->flush();
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('Não foi possivel salvar a alteração!');
                return $this->redirect()->toUrl(BASE_URL . '/auth/mensagens');
            }
        }
        exit;
    }

    public function usuariosAction() {
        $id = $this->params()->fromRoute('id', 0);
        if ($id > 0) {
            $usuarios = $this->getService('Auth\Service\Mensagens')->getUsers($id);
            echo $usuarios[0]['nome'];
            exit;
        } else {
            $buscar = mb_strtoupper($_GET['term'], 'UTF-8');
            $this->layout('layout/ajax-layout');
            $usuarios = $this->getService('Auth\Service\Mensagens')->getUsers($buscar);
        }
        $arrayColunas = array();
        if ($usuarios) {
            foreach ($usuarios as $chave => $c) {
                $arrayColunas[$c['id']] = $c['nome'];
            }
        }
        echo json_encode($arrayColunas);
        exit;
    }

    public function downloadAction() {
        $id = $this->params()->fromRoute('id', 0);
        $anx = $this->params()->fromRoute('anexo', 0);
        if ($id > 0) {
            $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
            $anexo = $this->getObjectManager()->getRepository('Auth\Model\AnexoMensagem')->findBy(array('mensagem' => $mensagem));
            $zip = new \ZipArchive();
            $zip_name = "$mensagem->assunto.zip";
            $zip->open(BASE_PROJECT . '/public/docs/zip/' . $zip_name, \ZipArchive::CREATE);
            foreach ($anexo as $key => $file) {
                $zip->addFromString($file->nome_arquivo, base64_decode(stream_get_contents($file->anexo)));
            }
            $zip->close();
            header('Content-type: application/zip');
            header("Content-disposition: attachment; filename=$zip_name");
            $arquivo = readfile(BASE_PROJECT . '/public/docs/zip/' . $zip_name);
            echo $arquivo;
            unlink(BASE_PROJECT . '/public/docs/zip/' . $zip_name);
            exit;
        } else if ($anx > 0) {
            $anexo = $this->getObjectManager()->find('Auth\Model\AnexoMensagem', $anx);
            $filename = $anexo->nome_arquivo;
            $filepath = BASE_PROJECT . '/public/docs/zip/' . $filename;
            $handle = fopen($filepath, 'w+');
            $conteudo = base64_decode(stream_get_contents($anexo->anexo));
            fwrite($handle, $conteudo);
            fclose($handle);
            $arquivo = file_get_contents($filepath);
            header("Content-type: $anexo->tipo");
            header("Content-disposition: attachment; filename=$anexo->nome_arquivo");
            echo $arquivo;
            exit;
        }
    }

}
