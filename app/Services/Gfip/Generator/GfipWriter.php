<?php

namespace App\Services\Gfip\Generator;

use App\Services\Gfip\Layout\GfipType00;
use App\Services\Gfip\Layout\GfipType10;
use App\Services\Gfip\Layout\GfipType30;
use App\Services\Gfip\Layout\GfipType90;
use Closure;
use Exception;

/**
 * Generates a GFIP (GRF) file according to bank "Caixa Econômica Federal"
 * layout. This implementation supports only records type 00, 10, 30 and 99,
 * whereas GfipType30 supports only employees type 13, unless specified
 * otherwise in its own class.
 */
class GfipWriter
{
    public bool $open = false;
    public int $recordsCount = 0;
    public bool $closed = false;

    private Closure $writeln;

    /**
     * @param Closure $writeln  Function able to append a new line to the result
     *                          file. Its SHOULD expect one string parameter to
     *                          append, and MAY return void.
     */
    public function __construct(Closure $writeln)
    {
        $this->writeln = $writeln;
    }

    public function beginFile(GfipType00 $type00)
    {
        $this->shouldNotBeOpen();
        $this->shouldNotBeClosed();
        
        $this->callWriteLn($type00->toLayoutRow());
        $this->open = true;
    }
    
    public function endFile(GfipType90 $type90)
    {
        $this->shouldBeOpen();        
        $this->shouldNotBeClosed();
        
        $this->callWriteLn($type90->toLayoutRow());
        $this->closed = true;
    }

    public function appendSection(GfipType10 $type10)
    {
        $this->shouldBeOpen();        
        $this->shouldNotBeClosed();

        $this->callWriteLn($type10->toLayoutRow());
    }

    public function appendRecord(GfipType30 $type30)
    {
        $this->shouldBeOpen();        
        $this->shouldNotBeClosed();
        
        $this->callWriteLn($type30->toLayoutRow());
        $this->recordsCount++;
    }

    private function shouldBeOpen()
    {
        if (! $this->open)
            throw new Exception("O documento ainda não foi aberto para alterações.");
    }

    private function shouldNotBeOpen()
    {
        if ($this->open)
            throw new Exception("O documento já foi aberto e não aceita duplicação de cabeçalho.");
    }

    private function shouldNotBeClosed()
    {
        if ($this->closed)
            throw new Exception("O documento já está fechado para alterações.");
    }
    
    private function callWriteLn(string $line)
    {
        $this->writeln->__invoke($line);
    }
}
