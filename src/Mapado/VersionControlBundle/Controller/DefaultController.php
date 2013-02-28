<?php

namespace Mapado\VersionControlBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use \Mapado\VersionControlBundle\Entity;
use \Mapado\VersionControlBundle\Model\Versionned;
use \Mapado\VersionControlBundle\Model\VersionNumberComparator;
use Mapado\VersionControlBundle\Exception\VersionControlException;

class DefaultController extends Controller
{
    /**
     * versionNumberAction
     *
     * @access public
     * @return Response
     *
     * @Route("/version_number/{taskName}/{versionnableType}:{versionnableId}", name="version_number")
     */
    public function versionNumberAction($taskName, $versionnableType, $versionnableId)
    {
        // get params
        $task = $this->getTask($taskName);
        $versionnable = $this->getVersionnable($versionnableType, $versionnableId);

        // get version
        $version = $this->get('mapado_versioncontroller')->getVersionNumber($versionnable, $task);

        return $this->returnJson($version);
    }

    /**
     * taskListAction
     *
     * @param string $versionnableType
     * @param string $versionnableId
     * @access public
     * @return Response
     *
     * @Route("/task_list/{versionnableType}:{versionnableId}", name="task_list")
     */
    public function taskListAction($versionnableType, $versionnableId)
    {
        // get params
        $versionnable = $this->getVersionnable($versionnableType, $versionnableId);

        // get version
        $versionList = $this->get('mapado_versioncontroller')->getTaskList($versionnable);

        return $this->returnJson($versionList);
    }

    /**
     * objectListAction
     *
     * @access public
     * @return void
     *
     * @Route("/object_list/{taskName}", name="task_object_list")
     */
    public function taskObjectListAction($taskName)
    {
        $task = $this->getTask($taskName);
        $objectList = $this->get('mapado_versioncontroller')->getObjectList($task);

        return $this->returnJson($objectList);
    }

    /**
     * objectListAction
     *
     * @param mixed $taskName
     * @param mixed $operator
     * @param mixed $versionComplete
     * @access public
     * @return void
     *
     * @Route(
     *     "/object_list/{taskName}/{operator}{versionComplete}",
     *     name="object_list",
     *     requirements={"operator" = ">=|>|<=|<|!=|="}
     * )
     */
    public function objectListAction($taskName, $operator, $versionComplete)
    {
        $task = $this->getTask($taskName);
        $vnc = new VersionNumberComparator($versionComplete, $operator);
        $objectList = $this->get('mapado_versioncontroller')->getObjectList($task, $vnc);

        return $this->returnJson($objectList);
    }

    /**
     * isValidAction
     *
     * @access public
     * @return void
     *
     * @Route(
     *     "/is_valid/{taskName}/{versionnableType}:{versionnableId}/{operator}{versionComplete}",
     *     name="is_valid",
     *     requirements={"operator" = ">=|>|<=|<|!=|="}
     * )
     */
    public function isValidAction($taskName, $versionnableType, $versionnableId, $operator, $versionComplete)
    {
        // get params
        $task = $this->getTask($taskName);
        $versionnable = $this->getVersionnable($versionnableType, $versionnableId);
        $vnc = new VersionNumberComparator($versionComplete, $operator);

        $valid = $this->get('mapado_versioncontroller')->isValid($versionnable, $task, $vnc);

        return $this->returnJson(array('is_valid' => $valid));
    }

    /**
     * deleteAction
     *
     * @access public
     * @return void
     *
     * @Route("/delete/{taskName}/{versionnableType}:{versionnableId}", name="delete")
     */
    public function deleteAction($taskName, $versionnableType, $versionnableId)
    {
        // get params
        $task = $this->getTask($taskName);
        $versionnable = $this->getVersionnable($versionnableType, $versionnableId);

        $this->get('mapado_versioncontroller')->delete($versionnable, $task);

        // return
        return $this->returnJson(array('deleted' => true));
    }

    /**
     * updateAction
     *
     * @access public
     * @return void
     *
     * @Route("/update/{taskName}/{versionnableType}:{versionnableId}/{versionComplete}", name="update")
     */
    public function updateAction($taskName, $versionnableType, $versionnableId, $versionComplete)
    {
        // get params
        $task = $this->getTask($taskName);
        $versionnable = $this->getVersionnable($versionnableType, $versionnableId, true);

        $vn = new Entity\VersionNumber($versionComplete);

        // create or update object
        $this->get('mapado_versioncontroller')->update($versionnable, $task, $vn);

        // return
        return $this->returnJson(array('updated' => true));
    }


    /**
     * getTask
     *
     * @param string $taskName
     * @access private
     * @return Task
     */
    private function getTask($taskName)
    {
        $task = $this->getManager()
                    ->getRepository('MapadoVersionControlBundle:Task', 'version_control')
                    ->findOneByName($taskName);

        if (!$task) {
            throw new VersionControlException('Task name is not valid');
        }
        return $task;
    }

    /**
     * getVersionnable
     *
     * @param string $type
     * @param string $id
     * @param boolean $createIfNotExists
     * @access public
     * @return Versionnable
     */
    private function getVersionnable($type, $id, $createIfNotExists = false)
    {
        $versionnable = $this->getManager()
                    ->getRepository('MapadoVersionControlBundle:Versionnable')
                    ->findOneBy(array('versionType' => $type, 'versionId' => $id));

        if (!$versionnable) {
            if ($createIfNotExists) {
                $versionnable = new Entity\Versionnable;
                $versionnable->setVersionType($type)
                            ->setVersionId($id);

                $this->getManager()->persist($versionnable);
                $this->getManager()->flush();
            } else {
                throw new VersionControlException('Versionnable object is not valid');
            }
        }

        return $versionnable;
    }

    /**
     * returnJson
     *
     * @param array $params
     * @access private
     * @return Response
     */
    private function returnJson($params)
    {
        // create a JSON-response with a 200 status code
        $response = new Response(json_encode($params));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * getManager get version control entity manager :
     * THIS IS AWFUL, I have to move those method into the VersionManager
     *
     * @access private
     * @return void
     */
    private function getManager()
    {
        return $this->get('doctrine')->getManager('version_control');
    }
}
