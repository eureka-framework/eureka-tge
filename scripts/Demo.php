<?php

/*
 * Copyright (c) Romain Cottard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Eureka\GameEngine\Script;

use Eureka\Component\Console\AbstractScript;
use Eureka\Component\Console\Color\Bit4StandardColor;
use Eureka\Component\Console\Color\Bit8StandardColor;
use Eureka\Component\Console\Help;
use Eureka\Component\Console\Option\Options;
use Eureka\Component\Console\Style\Style;
use Eureka\Component\Console\Terminal\Terminal;

class Demo extends AbstractScript
{
    public function __construct()
    {
        $this->setExecutable();
        $this->setDescription('Demo Script');

        $this->initOptions(
            (new Options())
        );
    }

    public function help(): void
    {
        (new Help(self::class, $this->declaredOptions(), $this->output(), $this->options()))
            ->display()
        ;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $terminal = new Terminal($this->output());
        $terminal->clear();

        $title    = ' This is demo script ';
        $subTitle = '(Press q or Esc to quit...)';

        $style  = (new Style($this->options()))
            ->background(Bit8StandardColor::White)
            ->color(Bit8StandardColor::Black)
        ;

        $width  = $terminal->getWidth();
        $height = $terminal->getHeight();

        $x = (int) floor(($width - \mb_strlen($title)) / 2);
        $y = (int) floor($height / 2) + 1;

        $terminal->cursor()->to($y, $x);

        $this->output()->write($style->apply($title . " ($x, $y) "));
        $terminal->cursor()->down(2);
        $terminal->cursor()->left(strlen($subTitle) + 2);
        $this->output()->write($subTitle);

        //~ set cursor to the end
        $terminal->cursor()->to($height, $width);
        while (true) {
            $key = $this->keypress();

            if ($key === 'q' || $key === "\x1B") {
                break;
            }
        }
    }

    private function keypress(): string
    {
        $input  = '';
        $read   = [STDIN];
        $write  = null;
        $except = null;

        readline_callback_handler_install('', function () {
            /* Nothing here */
        });

        do {
            $input .= fgetc(STDIN);
        } while (stream_select($read, $write, $except, 0, 1));

        readline_callback_handler_remove();

        return $input;
    }
}
