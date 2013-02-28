<?php

/*
Sample atoum configuration file.
Do "php path/to/test/file -c path/to/this/file" or "php path/to/atoum/scripts/runner.php -c path/to/this/file -f path/to/test/file" to use it.
*/

use \mageekguy\atoum;

/*
Write all on stdout.
*/
$stdOutWriter = new atoum\writers\std\out();

/*
Generate a CLI report.
*/
$cliReport = new atoum\reports\realtime\cli();
$cliReport->addWriter($stdOutWriter);

$runner->addReport($cliReport);

/*
 * Xunit report
 */
$xunit = new atoum\reports\asynchronous\xunit();
$runner->addReport($xunit);

/*
 * Xunit writer
 */
$writer = new atoum\writers\file(__DIR__ . '/logs/junit.xml');
$xunit->addWriter($writer);

/*
 * Html coverage
 */
$html = new \mageekguy\atoum\report\fields\runner\coverage\html('Coverage',  __DIR__ . '/coverage');
$cliReport->addField($html, array(\mageekguy\atoum\runner::runStop));
