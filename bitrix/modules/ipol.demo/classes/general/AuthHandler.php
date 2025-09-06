<?php
namespace Ipol\Demo;

use Ipol\Demo\Bitrix\Entity\Encoder;
use Ipol\Demo\Bitrix\Entity\Options;
use Ipol\Demo\Bitrix\Tools;
use Ipol\Demo\Demo\DemoApplication;

class AuthHandler extends AbstractGeneral
{
    public static function auth($params)
    {
        OptionsHandler::clearCache(true);

        $workTry = self::authRequest($params, false);

        if ($workTry === true) {
            echo '%Y%';
        } else {
            $workTry = self::authRequest($params, true);
            if ($workTry === true) {
                echo '%T%';
            } else {
                echo Tools::getMessage('AUTH_ERROR');
            }
        }
    }

    public static function authRequest($params, $testMode = false)
    {
        $encoder = new Encoder();
        $application = new DemoApplication(
            $params['apiKey'],
            $testMode,
            40,
            $encoder
        );

        if ($application->getErrorCollection()->getLast()) {
            return false;
        } else {
            self::login($params['apiKey']);
            Option::set('isTest',($testMode) ? 'Y' : 'N');

            return true;
        }
    }

    public static function login($key)
    {
        SubscribeHandler::register();

        Option::set('apiKey',$key);

        AgentHandler::addAgent('refreshStatuses', 1800);
        AgentHandler::addAgent('syncServiceData', 1800);
        AgentHandler::addAgent('unmakeOldFiles', 604800);

        OrderPropsHandler::controlProps();
    }

    public static function delogin()
    {
        Option::set('apiKey', false);
        Option::set('isTest', 'N');

        // Drop sync options while logout
        Option::set('sync_data_lastdate',   Option::getDefault('sync_data_lastdate'));
        Option::set('sync_data_step',       Option::getDefault('sync_data_step'));
        Option::set('sync_data_pagesize',   Option::getDefault('sync_data_pagesize'));
        Option::set('sync_data_pagenumber', Option::getDefault('sync_data_pagenumber'));
        Option::set('sync_data_completed',  Option::getDefault('sync_data_completed'));

        SubscribeHandler::unRegister();

        \CAgent::RemoveModuleAgents(self::$MODULE_ID);

        OptionsHandler::clearCache(true);

        if (Tools::isModuleAjaxRequest())
            echo 'Y';
    }

    public static function isAuthorized()
    {
        $options = new Options();
        return (bool) $options->fetchApiKey();
    }
}