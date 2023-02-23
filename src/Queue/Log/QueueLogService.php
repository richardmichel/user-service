<?php

namespace MichiServices\Common\Queue\Log;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;

class QueueLogService
{
    protected string $connectionLogQueue;

    public function __construct()
    {
        $this->connectionLogQueue = config('kongSurvey.connectionLogQueue');
    }

    /**
     * Retry an operation a given number of times.
     *
     * @param  int|array  $times
     * @param  callable  $callback
     * @param  int|\Closure  $sleepMilliseconds
     * @param  callable|null  $when
     * @param  string|null  $tableLogQueue
     * @return mixed
     *
     * @throws \Exception
     */
    function storeLogRetryQueue($times, callable $callback, $sleepMilliseconds = 500, $when = null, $tableLogQueue = 'queues.dead_letter_queue')
    {
        $f = new \ReflectionFunction($callback);
        $params = $f->getParameters(); // echo $params[0]->getDefaultValue();
        try {
            return retry($times, $callback, $sleepMilliseconds, $when);
        } catch (Throwable $e) {
            DB::connection($this->connectionLogQueue)->table($tableLogQueue)->insert([
                'message' => serialize(clone $params),
                'failed_at' => now(),
                'error_messages' => $e->getMessage()
            ]);
            return true;
        } catch (Exception $e) {

            DB::connection($this->connectionLogQueue)->table($tableLogQueue)->insert([
                'message' => serialize(clone $params),
                'failed_at' => now(),
                'error_messages' => $e->getMessage()
            ]);

            return true;
        }
    }
}
