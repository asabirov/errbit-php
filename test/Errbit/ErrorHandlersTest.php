<?

require_once(__DIR__ . "/../../lib/Errbit.php");

class ErrorHandlersTest extends PHPUnit_Framework_TestCase {

    protected function setUp()
    {
        Errbit::instance()
            ->configure(array(
                    'api_key' => '123',
                    'host' => 'http://localhost'
                ));
    }


	public function testExceptionChaining() {
        $exception = new Exception('test');

        $mock = $this->getMock('Errbit', array('notify'));
        $mock->expects($this->once())
            ->method('notify')
            ->with($exception);

        $prevHandlerCalled = false;
        $caughtException = null;
        set_exception_handler(function($e) use (&$prevHandlerCalled, &$caughtException) {
            $prevHandlerCalled = true;
            $caughtException = $e;
        });

        $handler = new Errbit_ErrorHandlers($mock, ['exception']);

        $handler->onException($exception);

		$this->assertTrue($prevHandlerCalled, 'Previous handler should be called');
        $this->assertEquals($caughtException, $exception, 'Exceptions should be identical');
	}
}
