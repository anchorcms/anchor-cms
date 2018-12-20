<?php

use System\Arr;

describe('arr', function () {
    beforeEach(function () {
        $this->testData = [
            'a'        => 42,
            'b'        => 0,
            'c'        => 12937548391823,
            'subArray' => [
                'foo' => 'test',
                'bar' => true,
                'baz' => null
            ],
            'quz'      => 12.513
        ];
    });

    it('should get a value from an array', function () {
        expect(Arr::get($this->testData, 'a'))
            ->to->equal(42);
    });

    it('should get a value from a keyed array', function () {
        expect(Arr::get([10, 20, 30], 1))
            ->to->equal(20);
    });

    it('should get a nested value from an array using dot syntax', function () {
        expect(Arr::get($this->testData, 'subArray.bar'))
            ->to->be->true();
    });

    it('should get a fallback value for a missing key', function () {
        expect(Arr::get($this->testData, 'missing key', 'He giveth, He taketh'))
            ->to->equal('He giveth, He taketh');
    });

    it('should get a fallback value for a wrong array argument', function () {
        expect(Arr::get(
            'definitely not an array',
            'x',
            'You be saved!'
        ))
            ->to->equal('You be saved!');
    });

    it('should set a key to an array', function () {
        Arr::set($this->testData, 'new_key', 10);

        expect(Arr::get($this->testData, 'new_key'))
            ->to->equal(10);
    });

    it('should remove a key from an array', function () {
        Arr::erase($this->testData, 'c');

        expect($this->testData)
            ->to->not->have->keys(['c']);
    });

    it('should do nothing on removing a missing key from an array', function () {
        $testData = $this->testData;

        Arr::erase($this->testData, 'missing key');

        expect($this->testData)
            ->to->deep->equal($testData);
    });

    it('should create an instance', function () {
        $myArr = Arr::create();

        expect($myArr)
            ->to->be->an->instanceof(Arr::class);
    });

    it('should retrieve the first item of an array', function () {
        $testData = Arr::create($this->testData);

        expect($testData->first())
            ->to->equal(42);
    });

    it('should retrieve the last item of an array', function () {
        $testData = Arr::create($this->testData);

        expect($testData->last())
            ->to->equal(12.513);
    });

    it('should shuffle an array', function () {
        $testData = Arr::create($this->testData);

        $stackProperty = new ReflectionProperty($testData, 'stack');
        $stackProperty->setAccessible(true);

        expect($stackProperty->getValue($testData))
            ->to->deep->equal($this->testData);

        $testData->shuffle();

        expect($stackProperty->getValue($testData))
            ->to->not->deep->equal($this->testData);

        // retrieve the respective arrays
        $shuffledKeys = $stackProperty->getValue($testData);
        $originalKeys = $this->testData;

        // sort both arrays to create a common order
        asort($shuffledKeys);
        asort($originalKeys);

        // expect all keys to be there
        expect($shuffledKeys)
            ->to->contain(...array_values($originalKeys));
    });
});
