<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Loan;
use app\models\User;

class LoanController extends Controller
{
    /**
     * обработка одной заявки
     * @param int $loanId ID заявки
     * @param int $delay Задержка в секундах
     */
    public function actionProcess($loanId, $delay = 0)
    {
        //поиск заявки
        $loan = Loan::findOne($loanId);
        if (!$loan) {
            echo "Loan not found: $loanId\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        //задержка
        if ($delay > 0) {
            sleep($delay);
        }

        // проверка нет ли у пользователя уже одобренных заявок
        $user = User::findOne($loan->user_id);
        if ($user && $user->hasApprovedLoans()) {
            $loan->status = Loan::STATUS_REJECTED;
            $loan->save();
            echo "Loan $loanId: Rejected (пользователь имеет одну одобренную заявку)\n";
            return ExitCode::OK;
        }

        // Рандомное решение: 10% вероятность одобрения
        $random = mt_rand(1, 100);
        if ($random <= 10) {
            $loan->status = Loan::STATUS_APPROVED;
            $message = "APPROVED";
        } else {
            $loan->status = Loan::STATUS_REJECTED;
            $message = "REJECTED";
        }

        if ($loan->save()) {
            echo "Заем $loanId: $message (рандом: $random)\n";
            return ExitCode::OK;
        } else {
            echo "Заем $loanId: ошибка в сохранении\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}