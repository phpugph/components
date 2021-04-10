<?php

namespace UGComponents\Curl;

use StdClass;
use PHPUnit\Framework\TestCase;
use UGComponents\Resolver\ResolverHandler;
use UGComponents\Profiler\InspectorHandler;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-07-27 at 02:10:59.
 */
class Curl_Rest_Test extends TestCase
{
  /**
   * @var CurlHandler
   */
  protected $object;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp(): void
  {
    $this->object = new Rest('http://foobar.com', function($options) {
      $options['response'] = json_encode($options);
      return $options;
    });
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown(): void
  {
  }

  /**
   * @covers UGComponents\Curl\Rest::__construct
   */
  public function test__construct()
  {
    $actual = Rest::i('http://foobar.com');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::__call
   * @covers UGComponents\Curl\Rest::getPath
   * @covers UGComponents\Curl\Rest::getKey
   * @covers UGComponents\Curl\Rest::send
   */
  public function test__call()
  {
    $actual = $this->object->friends()->getCommentsLike(1, 2);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->createCommentsLike(1, 2);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->postCommentsLike(1, 2);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->updateCommentsLike(1, 2);
    $this->assertEquals('PUT', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->putCommentsLike(1, 2);
    $this->assertEquals('PUT', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->removeCommentsLike(1, 2);
    $this->assertEquals('DELETE', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);

    $actual = $this->object->friends()->deleteCommentsLike(1, 2);
    $this->assertEquals('DELETE', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments/like/1/2', $actual[CURLOPT_URL]);
  }

  /**
   * @covers UGComponents\Curl\Rest::addHeader
   */
  public function testAddHeader()
  {
    $actual = $this->object->addHeader('Expect');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);

    $actual = $this->object->addHeader(['Expect']);
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);

    $actual = $this->object->addHeader('Authorization', 'foobar');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::addQuery
   */
  public function testAddQuery()
  {
    $actual = $this->object->addQuery(['foo' => 'bar']);
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);

    $actual = $this->object->addQuery('foo', 'bar');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::setUserAgent
   */
  public function testSetUserAgent()
  {
    $actual = $this->object->setUserAgent('Mozilla');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::setRequestFormat
   */
  public function testSetRequestFormat()
  {
    $actual = $this->object->setRequestFormat('query');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::setResponseFormat
   */
  public function testSetResponseFormat()
  {
    $actual = $this->object->setResponseFormat('json');
    $this->assertInstanceOf('\UGComponents\Curl\Rest', $actual);
  }

  /**
   * @covers UGComponents\Curl\Rest::send
   */
  public function testSend()
  {
    $rest1 = new Rest('http://foobar.com', function($options) {
      $options['response'] = json_encode($options);
      return $options;
    });

    $actual = $rest1
      ->setUserAgent('Mozilla')
      ->setRequestFormat('query')
      ->setResponseFormat('json')
      ->addHeader('Expect')
      ->addQuery('foo', 'bar')
      ->setBar('zoo')
      ->send('DELETE', '/friends/comments');

    $this->assertEquals('DELETE', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments?bar=zoo&foo=bar', $actual[CURLOPT_URL]);

    $rest2 = new Rest('http://foobar.com', function($options) {
      $options['response'] = json_encode($options);
      return $options;
    });

    $actual = $rest2
      ->setUserAgent('Mozilla')
      ->setRequestFormat('query')
      ->setResponseFormat('json')
      ->addHeader('Expect')
      ->addQuery('foo', 'bar')
      ->setBar('zoo')
      ->send('PUT', '/friends/comments');

    $this->assertEquals('PUT', $actual[CURLOPT_CUSTOMREQUEST]);
    $this->assertEquals('http://foobar.com/friends/comments?foo=bar', $actual[CURLOPT_URL]);
    $this->assertEquals('bar=zoo', $actual[CURLOPT_POSTFIELDS]);

    $rest3 = new Rest('http://foobar.com', function($options) {
      $options['response'] = json_encode($options);
      return $options;
    });

    $actual = $rest3
      ->setUserAgent('Mozilla')
      ->setRequestFormat('json')
      ->setResponseFormat('json')
      ->addHeader('Expect')
      ->addQuery('foo', 'bar')
      ->setBar('zoo')
      ->send('POST', '/friends/comments');

    $this->assertEquals('http://foobar.com/friends/comments?foo=bar', $actual[CURLOPT_URL]);
    $this->assertEquals('{"bar":"zoo"}', $actual[CURLOPT_POSTFIELDS]);

    $rest4 = new Rest('http://foobar.com', function($options) {
      $options['response'] = json_encode(array_values($options));
      return $options;
    });

    $actual = $rest4
      ->setUserAgent('Mozilla')
      ->setRequestFormat('query')
      ->setResponseFormat('raw')
      ->addHeader('Expect')
      ->addQuery('foo', 'bar')
      ->setBar('zoo')
      ->send('POST', '/friends/comments');

    $this->assertEquals(
      '["http:\/\/foobar.com\/friends\/comments?foo=bar",10,true,60,false,"Mozilla","bar=zoo",["Expect"],true]',
      $actual
    );

    $rest5 = new Rest('http://foobar.com', function($options) {
      $options['response'] = http_build_query($options);
      return $options;
    });

    $actual = $rest5
      ->setUserAgent('Mozilla')
      ->setRequestFormat('query')
      ->setResponseFormat('query')
      ->addHeader('Expect')
      ->addQuery('foo', 'bar')
      ->setBar('zoo')
      ->send('POST', '/friends/comments');

    $this->assertEquals('http://foobar.com/friends/comments?foo=bar', $actual[CURLOPT_URL]);
    $this->assertEquals('bar=zoo', $actual[CURLOPT_POSTFIELDS]);
  }
}
