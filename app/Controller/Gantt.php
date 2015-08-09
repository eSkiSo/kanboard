<?php

namespace Controller;

use Datetime;

/**
 * Gantt controller
 *
 * @package  controller
 * @author   Frederic Guillot
 */
class Gantt extends Base
{
    /**
     * Show Gantt chart for projects
     */
    public function project()
    {
        $project = $this->getProject();
        $this->response->html($this->template->layout('gantt/project', array(
            'tasks' => $this->taskFilter->gantt()->filterByProject($project['id'])->toGanttBars(),
            'project' => $project,
            'title' => t('Gantt chart for %s', $project['name']),
            'board_selector' => $this->projectPermission->getAllowedProjects($this->userSession->getId()),
        )));
    }

    /**
     * Save new task start date and due date
     */
    public function save()
    {
        $project = $this->getProject();
        $values = $this->request->getJson();

        $result = $this->taskModification->update(array(
            'id' => $values['id'],
            'date_started' => strtotime($values['start']),
            'date_due' => strtotime($values['end']),
        ));

        if (! $result) {
            $this->response->json(array('message' => 'Unable to save task'), 400);
        }

        $this->response->json(array('message' => 'OK'), 201);
    }
}
