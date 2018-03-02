<?php

namespace Tusimo\QueueEmailAlert;

use Illuminate\Contracts\Queue\ShouldQueue;

class QueueExceptionJob extends ExceptionJob implements ShouldQueue
{

}