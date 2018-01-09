<h1>FIRST TODOS</h1>
Replace all placeholders in the project. They are formatted as <pre>!!.*!!</pre>
<h1>BEFORE COMMIT</h1>
Run below command to fix source code regarding PSR-1 and PSR-2 coding styles.
<pre>php php-cs-fixer-v2.phar fix ./ --verbose --show-progress=estimating
</pre>
<h1>DOCTRINE ODM CONSOLE COMMANDS</h1>
<PRE>
odm:clear-cache:metadata - Clear all metadata cache of the various cache drivers.
odm:query - Query mongodb and inspect the outputted results from your document classes.
odm:generate:documents - Generate document classes and method stubs from your mapping information.
odm:generate:hydrators - Generates hydrator classes for document classes.
odm:generate:proxies - Generates proxy classes for document classes.
odm:generate:repositories - Generate repository classes from your mapping information.
odm:schema:create - Allows you to create databases, collections and indexes for your documents
odm:schema:drop - Allows you to drop databases, collections and indexes for your documents
odm:schema:update - Allows you to update indexes for your documents
odm:schema:shard - Allows you to enable sharding for your documents
<PRE>
http://docs.doctrine-project.org/projects/doctrine-mongodb-odm/en/latest/reference/console-commands.html
<h1>Async jobs</h1>
<h2>Handlers</h2>
A custom class implementing AsyncPHP\Doorman\Handler should be created. This handler takes a Task object. Using this
object, you can access any information about the task. In addition to getting information from the task, you can
implement the handle function in which way you want.
For example:
<pre>
class ReportHandler implements Handler
{
    /**
     * Handles a task.
     *
     * @param Task $task
     */
    public function handle(Task $task)
    {
        $data = $task->getData();
        /**@var Report $report */
        $report = $data["report"];
        $report->create();
    }
}
</pre>
<h2>Tasks</h2>
A custom class implementing AsyncPHP\Doorman\Task should be created. These tasks are then run by the handler.
For example:
<pre>
class ReportTask implements Task,Expires, Process
{
    private $report;
    /**
     * @var null|int
     */
    private $id;
    public function __construct(Report $report)
    {
        $this->report = $report;
    }
    /**
     * @return Report
     */
    public function getReport(){
        return $this->report;
    }
    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize($this->report);
    }
    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized
     * The string representation of the object.
     * 
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->report = unserialize($serialized);
    }
    /**
     * Gets the name of the handler class. This class will be used to handle this task.
     *
     * @return string
     */
    public function getHandler()
    {
        return "\\Classes\\Handler\\ReportHandler";
    }
    /**
     * Gets the data collected in this task.
     *
     * @return array
     */
    public function getData()
    {
        return ["report"=>$this->report];
    }
    /**
     * Instructs a manager to ignore any rules that would prevent this task from being immediately
     * handled.
     *
     * @return bool
     */
    public function ignoresRules()
    {
        return false;
    }
    /**
     * Instructs a manager to stop all tasks of the same type before running this task.
     *
     * @return bool
     */
    public function stopsSiblings()
    {
        return false;
    }
    /**
     * Check if this task is able to be run
     *
     * @return bool
     */
    public function canRunTask()
    {
        return true;
    }
    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * @return null|int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Gets the number of seconds until this task or process expires.
     *
     * @return int
     */
    public function getExpiresIn()
    {
        return -1;
    }
    /**
     * Checks whether a task or process should expire. This is called when a manager thinks a task
     * or process should expire.
     *
     * @param int $startedAt
     *
     * @return bool
     */
    public function shouldExpire($startedAt)
    {
        return false;
    }
}
</pre> 



