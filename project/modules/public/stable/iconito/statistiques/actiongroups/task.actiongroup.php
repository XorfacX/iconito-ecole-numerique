<?php

/**
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */
_classInclude("kernel|Tools");

class ActionGroupTask extends CopixActionGroup
{
  public function processSendStatistiques()
  {
    _classInclude('activityStream|ActivityStreamUnitTask');

    $activityStreamUnitTask = new ActivityStreamUnitTask();
    $activityStreamUnitTask->processStat();

    return _arNone();
  }
}