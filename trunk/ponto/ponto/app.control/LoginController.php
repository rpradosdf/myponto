<?php

class LoginController extends Controller {

    private $view;
    private $model;

    public function __construct(){
        // instanciamos os objetos
        $this->model = new LoginDao();
        $this->view = new View("login.html");
    }
  
    public function login()
    {
        // Valida usuario e sanha
        if (!$_POST['matricula'] && !$_POST['senha'])
        {
            $this->view->show();
        }
        else
        {
            // Checa login
            $oUser = new Usuario();
            $oUser->setLogin($_POST['matricula']);
            $oUser->setSenha($_POST['senha']);
            $varCodProfFuncao = $this->model->login($oUser);
            if(!$varCodProfFuncao)
            {
                // Redireciona para a página de login, com status de erro
                $this->view->setValue("MSG", "Usuário ou senha Inválidos");
                $this->view->show();
            }
            else
            {
                // Registra Sessão
                new Sessao();
                if(!Sessao::getValue('logado'))
                {
                    Sessao::SetValue('logado', true);
                }

                // Registra vari�veis globais
                $oProfDao = new ProfissionalDao();
                $oProf = $oProfDao->getProfissional($varCodProfFuncao);
                Sessao::setObject('oProf', $oProf);
                $oPeriodo = new Periodo(date("d-m-Y"));
                //$oPeriodo = new Periodo("2009-03-05");
                Sessao::setObject('oPeriodo', $oPeriodo);

                $oRegistro = new RegistroController;
                $oRegistro->show();
                $oRegistro = null;

/*
                // Redireciona para p�gina principal
                $oFreq = new FrequenciaController;
                $oFreq->exibirFrequencia();
                $oFreq = null;
                //return true;*/
            }
        }
        //return false;
    }

    public function Logout(){
        // Libera sess�o
        new Sessao();
        Sessao::free();
        $this->view->show();
    }
}    
?>
