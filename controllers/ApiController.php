<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\ContentNegotiator;
use app\models\Loan;
use app\models\User;

class ApiController extends Controller
{
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * POST /requests подача заявки на займ
     */
    public function actionRequests()
    {
        $request = Yii::$app->request;
        
        if (!$request->isPost) {
            Yii::$app->response->statusCode = 405;
            return [
                'result' => false, 
                'error' => 'Метод не разрешен'
            ];
        }

        $user_id = $request->post('user_id');
        $amount = $request->post('amount');
        $term = $request->post('term');

        if ($user_id === null || $amount === null || $term === null) {
            Yii::$app->response->statusCode = 400;
            return ['result' => false];
        }

        $user = User::findOne($user_id);
        if (!$user) {
            Yii::$app->response->statusCode = 400;
            return [
                'result' => false,
                'error' => 'Пользователь не найден'
            ];
        }

        //проверка есть ли у пользователя одобренные заявки
        if ($user->hasApprovedLoans()) {
            Yii::$app->response->statusCode = 400;
            return ['result' => false];
        }

        //создание заявки
        $loan = new Loan();
        $loan->user_id = (int)$user_id;
        $loan->amount = (int)$amount;
        $loan->term = (int)$term;

        if ($loan->save()) {
            Yii::$app->response->statusCode = 201;
            return [
                'result' => true,
                'id' => $loan->id
            ];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['result' => false];
        }
    }

    /**
     * GET /processor обработка заявок
     */
    public function actionProcessor()
    {
        $request = Yii::$app->request;
        $delay = $request->get('delay', 0);
        
        if (!is_numeric($delay) || $delay < 0) {
            Yii::$app->response->statusCode = 400;
            return [
                'result' => false,
                'error' => 'Неверный параметр задержки'
            ];
        }

        //все новые заявки
        $loans = Loan::find()
            ->where(['status' => Loan::STATUS_NEW])
            ->all();

        foreach ($loans as $loan) {
            $this->processLoanAsync($loan->id, (int)$delay);
        }

        return [
            'result' => true,
            'message' => 'Обработка началась для ' . count($loans) . ' заям'
        ];
    }

    /**
     * обработка одной заявки
     */
    private function processLoanAsync($loanId, $delay)
    {
        $scriptPath = Yii::getAlias('@app/yii');
        $command = "php " . escapeshellarg($scriptPath) . " loan/process " . (int)$loanId . " " . (int)$delay . " > /dev/null 2>&1 &";
        
        shell_exec($command);
    }
}