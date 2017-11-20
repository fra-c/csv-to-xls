<?php

namespace spec\App\Serializer;

use App\Entity\File;
use App\Serializer\FileJsonSerializer;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileJsonSerializerSpec extends ObjectBehavior
{
    function it_serialize_a_file(File $file)
    {
        $file->getId()->willReturn('some-id');
        $file->getFilename()->willReturn('some-filename');
        $file->getCreatedAt()->willReturn(new DateTimeImmutable('2017-11-10 00:00:00'));

        $this->beConstructedWith('data_files_path');

        $this->serializeFile($file)->shouldReturn(json_encode([
            'id' => 'some-id',
            'filename' => 'some-filename',
            'created_at' => '2017-11-10T00:00:00+0000',
            'link' => 'data_files_path/some-filename'
        ]));
    }

    function it_serialize_an_array_of_files(File $file1, File $file2)
    {
        $file1->getId()->willReturn('some-id-1');
        $file1->getFilename()->willReturn('some-filename-1');
        $file1->getCreatedAt()->willReturn(new DateTimeImmutable('2017-11-01 00:00:00'));

        $file2->getId()->willReturn('some-id-2');
        $file2->getFilename()->willReturn('some-filename-2');
        $file2->getCreatedAt()->willReturn(new DateTimeImmutable('2017-11-02 00:00:00'));

        $this->beConstructedWith('data_files_path');

        $this->serializeFileArray([$file1, $file2])->shouldReturn(json_encode([
            [
                'id' => 'some-id-1',
                'filename' => 'some-filename-1',
                'created_at' => '2017-11-01T00:00:00+0000',
                'link' => 'data_files_path/some-filename-1'
            ],
            [
                'id' => 'some-id-2',
                'filename' => 'some-filename-2',
                'created_at' => '2017-11-02T00:00:00+0000',
                'link' => 'data_files_path/some-filename-2'
            ]
        ]));
    }
}
