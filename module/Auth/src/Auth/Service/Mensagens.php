<?php

namespace Auth\Service;

use Core\Service\Service;
use Core\Model\EntityException as EntityException;

class Mensagens extends Service {

    public function getMensagens($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Mensagens.id', 'Mensagens.assunto', 'Mensagens.mensagem', 'Mensagens.status', 'Mensagens.desc_status', 'Mensagens.tipo_mensagem', 'Mensagens.data_criacao', 'Mensagens.data_leitura', 'Usuarios.nome', 'Usuarios.id as id_usuario', 'Mensagens.autor', 'Mensagens.favorito', 'Mensagens.anexo')
                ->from('Auth\Model\Mensagens', 'Mensagens')
                ->join('Mensagens.usuario', 'Usuarios')
                ->orderBy('Mensagens.data_criacao', 'DESC')
                ->where('Usuarios.id = ?1')
                ->setParameters(array(1 => $id));
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getUsers($buscar) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Usuario.id', 'Usuario.nome')
                ->from('Auth\Model\Usuario', 'Usuario')
                ->orderBy('Usuario.id', 'DESC')
                ->where('Usuario.nome LIKE ?1 OR Usuario.id = ?2')
                ->setParameter(1, '%' . $buscar . '%')
                ->setParameter(2, $buscar);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getMensagensNaoLidas($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Mensagens.id', 'Mensagens.assunto', 'Mensagens.mensagem', 'Mensagens.status', 'Mensagens.desc_status', 'Mensagens.tipo_mensagem', 'Mensagens.data_criacao', 'Mensagens.data_leitura', 'Usuarios.nome', 'Usuarios.id as id_usuario', 'Mensagens.autor', 'Mensagens.favorito', 'Mensagens.anexo')
                ->from('Auth\Model\Mensagens', 'Mensagens')
                ->join('Mensagens.usuario', 'Usuarios')
                ->orderBy('Mensagens.data_criacao', 'DESC')
                ->where('Usuarios.id = ?1 AND Mensagens.status != ?2')
                ->setParameters(array(1 => $id, 2 => 1));
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getMensagensLidas($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Mensagens.id', 'Mensagens.assunto', 'Mensagens.mensagem', 'Mensagens.status', 'Mensagens.desc_status', 'Mensagens.tipo_mensagem', 'Mensagens.data_criacao', 'Mensagens.data_leitura', 'Usuarios.nome', 'Usuarios.id as id_usuario', 'Mensagens.autor', 'Mensagens.favorito', 'Mensagens.anexo')
                ->from('Auth\Model\Mensagens', 'Mensagens')
                ->join('Mensagens.usuario', 'Usuarios')
                ->orderBy('Mensagens.data_criacao', 'DESC')
                ->where('Usuarios.id = ?1 AND Mensagens.status != ?2')
                ->setParameters(array(1 => $id, 2 => 0));
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getMensagensFavoritas($id) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Mensagens.id', 'Mensagens.assunto', 'Mensagens.mensagem', 'Mensagens.status', 'Mensagens.desc_status', 'Mensagens.tipo_mensagem', 'Mensagens.data_criacao', 'Mensagens.data_leitura', 'Usuarios.nome', 'Usuarios.id as id_usuario', 'Mensagens.autor', 'Mensagens.favorito', 'Mensagens.anexo')
                ->from('Auth\Model\Mensagens', 'Mensagens')
                ->join('Mensagens.usuario', 'Usuarios')
                ->orderBy('Mensagens.data_criacao', 'DESC')
                ->where('Usuarios.id = ?1 AND Mensagens.favorito != ?2')
                ->setParameters(array(1 => $id, 2 => 0));
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }

    public function getMensagensEnviadas($nome) {
        $select = $this->getObjectManager()->createQueryBuilder()
                ->select('Mensagens.id', 'Mensagens.assunto', 'Mensagens.mensagem', 'Mensagens.status', 'Mensagens.desc_status', 'Mensagens.tipo_mensagem', 'Mensagens.data_criacao', 'Mensagens.data_leitura', 'Usuarios.nome', 'Usuarios.id as id_usuario', 'Mensagens.autor', 'Mensagens.favorito', 'Mensagens.anexo')
                ->from('Auth\Model\Mensagens', 'Mensagens')
                ->join('Mensagens.usuario', 'Usuarios')
                ->orderBy('Mensagens.data_criacao', 'DESC')
                ->where('Mensagens.autor LIKE ?1 AND Mensagens.desc_status IS NULL')
                ->setParameter(1, $nome);
        $query = $select->getQuery();
        $result = $query->getResult();
        return $result;
    }
    
    public function esquecerEnviada($mensagem){
        $mensagem->desc_status = 'enviada';
        $this->getObjectManager()->persist($mensagem);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }

    public function marcarLido($ids) {
        foreach ($ids as $id) {
            $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
            $horario = new \DateTime("now");
            $mensagem->status = 1;
            $mensagem->data_leitura = $horario;
            $this->getObjectManager()->persist($mensagem);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }
    
    public function marcarLidoMsg($mensagem) {
        $horario = new \DateTime("now");
        $mensagem->status = 1;
        $mensagem->data_leitura = $horario;
        $this->getObjectManager()->persist($mensagem);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }

    public function marcarNaoLido($ids) {
        foreach ($ids as $id) {
            $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
            $horario = new \DateTime("now");
            $mensagem->status = 0;
            $mensagem->data_leitura = $horario;
            $this->getObjectManager()->persist($mensagem);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }

    public function marcaFavorito($ids) {
        foreach ($ids as $id) {
            $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
            $horario = new \DateTime("now");
            $mensagem->favorito = 1;
            $this->getObjectManager()->persist($mensagem);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }

    public function desmarcaFavorito($ids) {
        foreach ($ids as $id) {
            $mensagem = $this->getObjectManager()->find('Auth\Model\Mensagens', $id);
            $horario = new \DateTime("now");
            $mensagem->favorito = 0;
            $this->getObjectManager()->persist($mensagem);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException('Erro ao armazenar dados, tente novamente mais tarde');
        }
    }

    /**
     * Função para criar mensagens e vincular a usuarios
     * @author Felippe S. R. dos Santos <felippe.omgt@gmail.com>
     * @params $user = id do usuario;
     * @params $msg = Corpo da mensagem;
     * @params $subject = Assunto da mensagem;
     * @params $type = tipo da mensagem; ex: Alerta, Notificação, DEFAULT: Mensagem.
     */
    public function newMessage($usuarios, $msg, $autor, $anexo) {
        $data_criacao = new \DateTime("now");
        foreach ($usuarios as $usuario) {
            $mensagem = new \Auth\Model\Mensagens();
            $mensagem->usuario = $usuario;
            $mensagem->assunto = $msg['assunto'];
            $mensagem->mensagem = $msg['mensagem'];
            $mensagem->status = 0;
            $mensagem->tipo_mensagem = $msg['tipo_mensagem'];
            $mensagem->data_criacao = $data_criacao;
            $mensagem->autor = $autor;
            $this->getObjectManager()->persist($mensagem);
            if ($anexo[0]['error'] != 4) {
                $mensagem->anexo = 1;
                $this->saveAnexos($mensagem, $anexo);
            } else {
                $mensagem->anexo = 0;
            }
            $this->getObjectManager()->persist($mensagem);
            $mensagens[] = $mensagem;
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
        }
//        return $mensagens;
    }

    public function newMessageExportacao($usuario, $msg){
        $data_criacao = new \DateTime("now");
        $data_criacao = $data_criacao->format('Y-m-d H:i:s');
        $connection = $this->getService('Main\Service\ConnectDB')->connectDb();
        $sql = "INSERT INTO public.dm_mensagens (usuario, assunto, mensagem, status, tipo_mensagem, data_criacao, autor, anexo) VALUES ($usuario, '$msg[assunto]', '$msg[mensagem]', 0, 'Notificação', '$data_criacao', 'Sistema DIMO', 0)";

        pg_query($connection, $sql);
    }

    public function saveAnexos($mensagem, $anexos) {
        foreach ($anexos as $file) {
            $anexo = new \Auth\Model\AnexoMensagem();
            $anexo->mensagem = $mensagem;
            $anexo->nome_arquivo = $file['name'];
            $anexo->tipo = $file['type'];
            $anexo->tamanho = $file['size'];
            $fc = base64_encode(file_get_contents($file['tmp_name'])); 
            $anexo->anexo = $fc;
            $this->getObjectManager()->persist($anexo);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
        }
    }
    
    public function resposta($mensagem_original, $resposta, $autor){
        $data_criacao = new \DateTime("now");
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('nome'=>$mensagem_original->autor));
//        var_dump($usuario);exit;
        $mensagem = new \Auth\Model\Mensagens();
        $mensagem->usuario = $usuario;
        $mensagem->assunto = 'Resposta: '.trim($mensagem_original->assunto, 'Resposta: ');
        $mensagem->mensagem = $resposta;
        $mensagem->status = 0;
        $mensagem->tipo_mensagem = $mensagem_original->tipo_mensagem;
        $mensagem->data_criacao = $data_criacao;
        $mensagem->autor = $autor;
        $this->getObjectManager()->persist($mensagem);
        try {
            $this->getObjectManager()->flush();
            $flag = 'true';
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
            $flag = 'false';
        }  
        return $flag;
    }

    public function saveMensagem($user, $msg, $subject, $type, $autor) {
        $usuarios = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findBy(array('id' => $user));
        $data_criacao = new \DateTime("now");
        foreach ($usuarios as $usuario) {
            $mensagem = new \Auth\Model\Mensagens();
            $mensagem->usuario = $usuario;
            $mensagem->assunto = $subject;
            $mensagem->mensagem = $msg;
            $mensagem->status = 0;
            $mensagem->tipo_mensagem = $type;
            $mensagem->data_criacao = $data_criacao;
            $mensagem->autor = $autor;
            $this->getObjectManager()->persist($mensagem);
            $mensagens[] = $mensagem;
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
        }
        return $mensagens;
    }

    public function saveMsg($user, $msg, $subject, $type, $autor) {
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario', $user);
        $data_criacao = new \DateTime("now");
        $mensagem = new \Auth\Model\Mensagens();
        $mensagem->usuario = $usuario;
        $mensagem->assunto = $subject;
        $mensagem->mensagem = $msg;
        $mensagem->status = 0;
        $mensagem->tipo_mensagem = $type;
        $mensagem->data_criacao = $data_criacao;
        $mensagem->autor = $autor;
        $this->getObjectManager()->persist($mensagem);
        $mensagens = $mensagem;
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
        }
        return $mensagens;
    }

    public function saveMensagens($user, $msg, $subject, $type, $autor, $auth) {
        $data_criacao = new \DateTime("now");
        foreach ($user as $users) {
            $usuario = $this->getObjectManager()->find('Auth\Model\Usuario', $users->id_usuario->id);
            $mensagem = new \Auth\Model\Mensagens();
            $mensagem->usuario = $usuario;
            $mensagem->assunto = $subject;
            $mensagem->mensagem = $msg;
            $mensagem->status = 0;
            $mensagem->tipo_mensagem = $type;
            $mensagem->data_criacao = $data_criacao;
            $mensagem->autor = $autor->nome;
            $mensagem->solicitacao = $auth;
            $this->getObjectManager()->persist($mensagem);
            $mensagens[] = $mensagem;
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a mensagem.");
        }
        return $mensagens;
    }

    public function saveAutorizacaoConciliacao($autor, $processo, $acao, $flag) {
        $usuario = $this->getObjectManager()->getRepository('Auth\Model\Usuario')->findOneBy(array('login' => $autor->login));
        $autorizacao = new \Auth\Model\AutorizacaoMensagens();
        $autorizacao->usuario = $usuario;
        $autorizacao->processo = $processo;
        $autorizacao->acao = $acao;
        $autorizacao->flags = $flag;
        $autorizacao->pass = 0;
        $this->getObjectManager()->persist($autorizacao);
        $auth = $autorizacao;
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel salvar a autorização.");
        }
        return $autorizacao;
    }

    public function deletarMensagem($mensagem) {
        $this->getObjectManager()->remove($mensagem);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel deletar a mensagem.");
        }
    }
    
    public function deletarAnexo($anexos){
        foreach($anexos as $anexo){
            $this->getObjectManager()->remove($anexo);
        }
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possivel deletar a mensagem.");
        }
    }

    public function removerAutorizacoes($solicitacao) {
        $mensagens = $this->getObjectManager()->getRepository('Auth\Model\Mensagens')->findBy(array('solicitacao' => $solicitacao));
        foreach ($mensagens as $mensagem) {
            $this->deletarMensagem($mensagem);
        }
        $this->getObjectManager()->remove($solicitacao);
        try {
            $this->getObjectManager()->flush();
        } catch (\Exception $e) {
            throw new EntityException("Não foi possível remover a autorização");
        }
    }

}
