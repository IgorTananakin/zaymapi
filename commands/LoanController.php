<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\Loan;
use app\models\User;

class LoanController extends Controller
{
    /**
     * Обработка одной заявки
     * @param int $loanId ID заявки
     * @param int $delay Задержка в секундах
     */
    public function actionProcess($loanId, $delay = 0)
    {
        // Ищем заявку
        $loan = Loan::findOne($loanId);
        if (!$loan) {
            echo "Loan not found: $loanId\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }

        // Эмулируем задержку обработки
        if ($delay > 0) {
            sleep($delay);
        }

        // Проверяем, нет ли у пользователя уже одобренных заявок
        $user = User::findOne($loan->user_id);
        if ($user && $user->hasApprovedLoans()) {
            // Если уже есть одобренная заявка - отклоняем текущую
            $loan->status = Loan::STATUS_REJECTED;
            $loan->save();
            echo "Loan $loanId: Rejected (user already has approved loan)\n";
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

        // Сохраняем результат
        if ($loan->save()) {
            echo "Loan $loanId: $message (random: $random)\n";
            return ExitCode::OK;
        } else {
            echo "Loan $loanId: Error saving\n";
            return ExitCode::UNSPECIFIED_ERROR;
        }
    }
}