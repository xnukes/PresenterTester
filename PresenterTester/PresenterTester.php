<?php

namespace PresenterTester;

class PresenterTester extends \Nette\Object {

    /** @var \Nette\Application\IPresenterFactory */
    private $presenterFactory;

    /** @var string */
    private $action = 'default';

    /** @var string */
    private $presenter;

    /** @var string */
    private $handle;

    /** @var array */
    private $params = array();

    /** @var array */
    private $post = array();

    /** @var \Nette\Application\UI\Presenter */
    private $presenterComponent;

    /** @var \Nette\Application\Request */
    private $request;

    /** @var \Nette\Application\IResponse */
    private $response;

    /**
     * @param \Nette\Application\IPresenterFactory $presenterFactory
     */
    public function __construct(\Nette\Application\IPresenterFactory $presenterFactory) {
        $this->presenterFactory = $presenterFactory;
    }

    /**
     * Let's run the presenter!
     * @return \Nette\Application\IResponse
     */
    public function run() {
        if ($this->isResponseCreated()) {
            throw new LogicException("You can not run one presenter twice. You maybe want use clean() method first.");
        }
        $presenter = $this->createPresenter();
        $request = $this->createRequest();
        return $this->response = $presenter->run($request);
    }

    /**
     * Cleans status.
     */
    public function clean() {
        $this->response = $this->presenterComponent = $this->request = NULL;
    }

    /**
     * @return \Nette\Application\UI\Presenter
     */
    private function createPresenter() {
        if ($this->presenterComponent !== NULL) {
            return $this->presenterComponent;
        }
        $presenter = $this->presenterFactory->createPresenter($this->presenter);
        $presenter->autoCanonicalize = FALSE;
        return $this->presenterComponent = $presenter;
    }

    /**
     * @return \Nette\Application\Request
     */
    private function createRequest() {
        if ($this->request !== NULL) {
            return $this->request;
        }

        $method = 'GET';

        $params = $this->params;
        $params['action'] = $this->action;

        $post = $this->post;
        if ($post) {
            $method = 'POST';
        }

        if ($this->handle) {
            $params['do'] = $this->handle;
        }

        $request = new \Nette\Application\Request($this->presenter, $method, $params, $post);

        return $this->request = $request;
    }

    /**
     * Sets presenter name
     * @param string $presenter
     * @return \PresenterTester\PresenterTester
     * @throws \PresenterTester\LogicException
     */
    public function setPresenter($presenter) {
        if ($this->isPresenterCreated()) {
            throw new LogicException("Changing presenter name after creation of presenter has no effect. You maybe want use clean() method first.");
        }
        if ($this->isRequestCreated()) {
            throw new LogicException("Changing presenter name after creation of request has no effect. You maybe want use clean() method first.");
        }
        $this->presenter = $presenter;
        return $this;
    }

    /**
     * @return string name of presenter
     */
    public function getPresenter() {
        return $this->presenter;
    }

    /**
     * @param string $handle
     * @return \PresenterTester\PresenterTester
     * @throws \PresenterTester\LogicException
     */
    public function setHandle($handle) {
        if ($this->isRequestCreated()) {
            throw new LogicException("Changing handle parameter after creation of request has no effect. You maybe want use clean() method first.");
        }
        $this->handle = $handle;
        return $this;
    }

    /**
     * @return string Handle name
     */
    public function getHandle() {
        return $this->handle;
    }

    /**
     * @param string $action
     * @return \PresenterTester\PresenterTester
     * @throws \PresenterTester\LogicException
     */
    public function setAction($action) {
        if ($this->isRequestCreated()) {
            throw new LogicException("Changing action after creation of request has no effect. You maybe want use clean() method first.");
        }
        $this->action = $action;
        return $this;
    }

    /**
     * @return string action name
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Sets GET parameters.
     * @param array $params
     * @return \PresenterTester\PresenterTester
     * @throws \PresenterTester\LogicException
     */
    public function setParams(array $params) {
        if ($this->isRequestCreated()) {
            throw new LogicException("Changing parameters after creation of request has no effect. You maybe want use clean() method first.");
        }
        $this->params = $params;
        return $this;
    }

    /**
     * @return array of parameters
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Sets POST parameters.
     * @param array $params
     * @return \PresenterTester\PresenterTester
     * @throws \PresenterTester\LogicException
     */
    public function setPost(array $params) {
        if ($this->isRequestCreated()) {
            throw new LogicException("Changing POST parameters after creation of request has no effect. You maybe want use clean() method first.");
        }
        $this->post = $params;
        return $this;
    }

    /**
     * @return array of POST parameters
     */
    public function getPost() {
        return $this->post;
    }

    /**
     * @return \Nette\Application\UI\Presenter
     */
    public function getPresenterComponent() {
        return $this->createPresenter();
    }

    /**
     * @return \Nette\Application\Request
     */
    public function getRequest() {
        return $this->createRequest();
    }

    /**
     * @return \Nette\Application\IResponse
     * @throws \PresenterTester\LogicException
     */
    public function getResponse() {
        if (!$this->isResponseCreated()) {
            throw new LogicException("You can not get response before use run() menthod.");
        }
        return $this->response;
    }

    /**
     * @return boolean
     */
    private function isResponseCreated() {
        return $this->response !== NULL;
    }

    /**
     * @return boolean
     */
    private function isPresenterCreated() {
        return $this->presenterComponent !== NULL;
    }

    /**
     * @return boolean
     */
    private function isRequestCreated() {
        return $this->request !== NULL;
    }

}

