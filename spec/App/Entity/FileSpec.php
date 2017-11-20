<?php

namespace spec\App\Entity;

use App\Entity\File;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class FileSpec extends ObjectBehavior
{
    function let ()
    {
        $this->beConstructedWithFilename('filename');
    }

    function it_exposes_the_filename()
    {
        $this->getFilename()->shouldReturn('filename');
    }

    function it_exposes_the_id()
    {
        $this->getId()->shouldMatch('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i');
    }

    function it_exposes_the_creation_date()
    {
        $this->getCreatedAt()->shouldReturnAnInstanceOf(DateTimeImmutable::class);
    }
}
