<?php

require_once("./vendor/autoload.php");

use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\TestCase as TestCase;
use GuzzleHttp\Client;

class ProjectTest extends TestCase
{
    const URI_PROJECT = 'projects';

    const URI_TASK = 'tasks';
    private ?Client $http;

    public function setUp(): void
    {
        $this->http = new Client(['base_uri' => 'http://localhost:8660/']);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testPost(): void {
        try {
            $projectId = $this->postProject();

            $taskId = $this->postTask($projectId);

            $this->deleteTask($taskId);

            $this->deleteProject($projectId);
        } catch(Exception $e) {
            echo($e);
        }
    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testRead(): void {
        try {
            $id = $this->postProject();
            $this->postTask($id);

            $response = $this->http->get(ProjectTest::URI_PROJECT . "/" .  $id);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $project = json_decode($response->getBody());
            $this->assertIsInt($project->id);
            $this->assertIsString($project->title);
            $this->assertIsArray($project->copil_list);
            $this->assertIsArray($project->status_columns);

            // test task in body
            $firstColumn = $project->status_columns[0];
            $firstTask = $firstColumn->tasks[0];
            $this->assertIsInt($firstTask->id);
            $this->assertIsString($firstTask->title);
            $this->assertEquals("Description de la tache de test", $firstTask->description);
            $this->assertIsString($firstTask->description);

            // test pilot in body
            $this->assertIsInt($firstTask->pilot->id);

            $this->deleteProject($id);
        } catch(Exception $e) {
            echo($e);
        }

    }

    /**
     * @return void
     * @throws GuzzleException
     */
    public function testSearch(): void {
        try {
            $id = $this->postProject();

            $response = $this->http->get(ProjectTest::URI_PROJECT);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $projects = json_decode($response->getBody());
            $this->assertIsArray($projects);
            $firstProject = $projects[0];
            $this->assertIsInt($firstProject->id);
            $this->assertIsString($firstProject->title);
            $this->assertIsArray($firstProject->copil_list);

            $this->deleteProject($id);
        } catch(Exception $e) {
            echo($e);
        }

    }

    /**
     * @return int|null
     * @throws GuzzleException
     */
    private function postProject(): ?int {
        try {
            $response = $this->http->post(ProjectTest::URI_PROJECT, [
                'json' => [
                    'title' => 'Projet Test',
                    'copil_list' => [1, 2, 3]
                ]
            ]);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $project = json_decode($response->getBody());
            $this->assertIsInt($project->id);
            $this->assertIsString($project->title);
            $this->assertIsArray($project->copil_list);
            return $project->id;
        } catch(Exception $e) {
            echo($e);
            return null;
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws GuzzleException
     */
    private function deleteProject(int $id): void {
        try {
            $response = $this->http->delete(ProjectTest::URI_PROJECT . "/" . $id);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $body = json_decode($response->getBody());
            $this->assertIsString($body->message);
        } catch(Exception $e) {
            echo($e);
        }
    }

    /**
     * @param int $projectId
     * @return int|null
     * @throws GuzzleException
     */
    private function postTask(int $projectId): ?int {
        try {
            $response = $this->http->post(ProjectTest::URI_TASK, [
                'json' => [
                    'title' => 'Tache Test',
                    "description" => "Description de la tache de test",
                    "start_date" => "2022-06-30",
                    "end_date" => "2022-08-22",
                    "sector" => "informatique",
                    "pilot" => 1,
                    "project_id" => $projectId
                ]
            ]);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $task = json_decode($response->getBody());
            $this->assertIsInt($task->id);
            $this->assertIsString($task->title);
            $this->assertIsString($task->description);
            return $task->id;
        } catch(Exception $e) {
            echo($e);
            return null;
        }
    }

    /**
     * @param int $id
     * @return void
     * @throws GuzzleException
     */
    private function deleteTask(int $id): void {
        try {
            $response = $this->http->delete(ProjectTest::URI_TASK . "/" . $id);

            // test response code
            $this->assertEquals(200, $response->getStatusCode());

            // test header
            $contentType = $response->getHeaders()["Content-Type"][0];
            $this->assertEquals("application/json; charset=utf-8", $contentType);

            // test body
            $body = json_decode($response->getBody());
            $this->assertIsString($body->message);
        } catch(Exception $e) {
            echo($e);
        }
    }
}