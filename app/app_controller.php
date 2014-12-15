<?php
class AppController extends Controller
{
    public $default_view_class = 'AppLayoutView';
    protected $player = null;

    public function dispatchAction()
    {
        if (!self::isAction($this->action)) {
            // アクション名が予約語などで正しくないとき
            throw new DCException('is invalid');
        }

        if (!method_exists($this, '__call')) {
            if (!method_exists($this, $this->action)) {
                // アクションがコントローラに存在しないとき
                throw new DCException('does not exist');
            }
            $method = new ReflectionMethod($this, $this->action);
            if (!$method->isPublic()) {
                // アクションが public メソッドではないとき
                throw new DCException('is not public');
            }
        }

        // アクションの実行
        $this->{$this->action}();

        $this->render();
    }

    public function start()
    {
        $player = new Player();
        if (!is_null($this->player)) {
            return $this->player;
        }
        $client_id = Param::get('facebook_connector_id', 'dummy');
        $this->player = $player->getByClientId($client_id);
        apache_note("player_id", $this->player->id);
        return $this->player;
    }
}
