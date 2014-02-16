<?php

/**
 * Underscore.php v1.0
 * Copyright (c) 2013 Jonathan Aquino
 *
 * This is a fork of Brian Haveri's Underscore.php. I have removed the
 * object-oriented way of calling the functions, and changed all functions to
 * static. This eliminates the E_STRICT warnings under PHP >=5.4.
 *
 * Underscore.php is licensed under the MIT license
 * Underscore.php was inspired by and borrowed from Underscore.js
 * For docs, license, tests, and downloads, see: https://github.com/JonathanAquino/Underscore.php
 */

// Underscore.php
class __ {

  // Invoke the iterator on each item in the collection
  public static function each($collection=null, $iterator=null) {
    if(is_null($collection)) return null;

    $collection = (array) self::_collection($collection);
    if(count($collection) === 0) return null;

    foreach($collection as $k=>$v) {
      call_user_func($iterator, $v, $k, $collection);
    }
    return null;
  }


  // Return an array of values by mapping each item through the iterator
  // map alias: collect
  public static function collect($collection=null, $iterator=null) { return self::map($collection, $iterator); }
  public static function map($collection=null, $iterator=null) {
    if(is_null($collection)) return array();

    $collection = (array) self::_collection($collection);
    if(count($collection) === 0) array();

    $return = array();
    foreach($collection as $k=>$v) {
      $return[] = call_user_func($iterator, $v, $k, $collection);
    }
    return $return;
  }


  // Reduce a collection to a single value
  // reduce aliases: foldl, inject
  public static function foldl($collection=null, $iterator=null, $memo=null) { return self::reduce($collection, $iterator, $memo); }
  public static function inject($collection=null, $iterator=null, $memo=null) { return self::reduce($collection, $iterator, $memo); }
  public static function reduce($collection=null, $iterator=null, $memo=null) {
    if(!is_object($collection) && !is_array($collection)) {
      if(is_null($memo)) throw new Exception('Invalid object');
      else return $memo;
    }

    return array_reduce($collection, $iterator, $memo);
  }


  // Right-associative version of reduce
  // reduceRight alias: foldr
  public static function foldr($collection=null, $iterator=null, $memo=null) { return self::reduceRight($collection, $iterator, $memo); }
  public static function reduceRight($collection=null, $iterator=null, $memo=null) {
    if(!is_object($collection) && !is_array($collection)) {
      if(is_null($memo)) throw new Exception('Invalid object');
      else return $memo;
    }

    krsort($collection);

    $__ = new self;
    return $__->reduce($collection, $iterator, $memo);
  }


  // Extract an array of values for a given property
  public static function pluck($collection=null, $key=null) {
    $collection = (array) self::_collection($collection);

    $return = array();
    foreach($collection as $item) {
      foreach($item as $k=>$v) {
        if($k === $key) $return[] = $v;
      }
    }
    return $return;
  }


  // Does the collection contain this value?
  // includ alias: contains
  public static function contains($collection=null, $val=null) { return self::includ($collection, $val); }
  public static function includ($collection=null, $val=null) {
    $collection = (array) self::_collection($collection);

    return (array_search($val, $collection, true) !== false);
  }


  // Invoke the named function over each item in the collection, optionally passing arguments to the function
  public static function invoke($collection=null, $function_name=null, $arguments=null) {
    $args = func_get_args();
    $__ = new self;
    list($collection, $function_name) = $__->first($args, 2);
    $arguments = $__->rest(func_get_args(), 2);

    // If passed an array or string, return an array
    // If passed an object, return an object
    $is_obj = is_object($collection);
    $result = (empty($arguments)) ? array_map($function_name, (array) $collection) : array_map($function_name, (array) $collection, $arguments);
    if($is_obj) $result = (object) $result;

    return $result;
  }


  // Does any values in the collection meet the iterator's truth test?
  // any alias: some
  public static function some($collection=null, $iterator=null) { return self::any($collection, $iterator); }
  public static function any($collection=null, $iterator=null) {
    $collection = self::_collection($collection);

    $__ = new self;
    if(!is_null($iterator)) $collection = $__->map($collection, $iterator);
    if(count($collection) === 0) return false;

    return is_int(array_search(true, $collection, false));
  }


  // Do all values in the collection meet the iterator's truth test?
  // all alias: every
  public static function every($collection=null, $iterator=null) { return self::all($collection, $iterator); }
  public static function all($collection=null, $iterator=null) {
    $collection = self::_collection($collection);

    $__ = new self;
    if(!is_null($iterator)) $collection = $__->map($collection, $iterator);
    $collection = (array) $collection;
    if(count($collection) === 0) return true;

    return is_bool(array_search(false, $collection, false));
  }


  // Return an array of values that pass the truth iterator test
  // filter alias: select
  public static function select($collection=null, $iterator=null) { return self::filter($collection, $iterator); }
  public static function filter($collection=null, $iterator=null) {
    $collection = self::_collection($collection);

    $return = array();
    foreach($collection as $val) {
      if(call_user_func($iterator, $val)) $return[] = $val;
    }
    return $return;
  }


  // Return an array where the items failing the truth test are removed
  public static function reject($collection=null, $iterator=null) {
    $collection = self::_collection($collection);

    $return = array();
    foreach($collection as $val) {
      if(!call_user_func($iterator, $val)) $return[] = $val;
    }
    return $return;
  }


  // Return the value of the first item passing the truth iterator test
  // find alias: detect
  public static function detect($collection=null, $iterator=null) { return self::find($collection, $iterator); }
  public static function find($collection=null, $iterator=null) {
    $collection = self::_collection($collection);

    foreach($collection as $val) {
      if(call_user_func($iterator, $val)) return $val;
    }
    return false;
  }


  // How many items are in this collection?
  public static function size($collection=null) {
    $collection = self::_collection($collection);

    return count((array) $collection);
  }


  // Get the first element of an array. Passing n returns the first n elements.
  // first alias: head
  public static function head($collection=null, $n=null) { return self::first($collection, $n); }
  public static function first($collection=null, $n=null) {
    $collection = self::_collection($collection);

    if($n === 0) return array();
    if(is_null($n)) return current(array_splice($collection, 0, 1, true));
    return array_splice($collection, 0, $n, true);
  }


  // Get the rest of the array elements. Passing n returns from that index onward.
  public static function tail($collection=null, $index=null) { return self::rest($collection, $index); }
  public static function rest($collection=null, $index=null) {
    if(is_null($index)) $index = 1;

    $collection = self::_collection($collection);

    return array_splice($collection, $index);
  }


  // Return everything but the last array element. Passing n excludes the last n elements.
  public static function initial($collection=null, $n=null) {
    $collection = (array) self::_collection($collection);

    if(is_null($n)) $n = 1;
    $first_index = count($collection) - $n;
    $__ = new self;
    return $__->first($collection, $first_index);
  }


  // Get the last element from an array. Passing n returns the last n elements.
  public static function last($collection=null, $n=null) {
    $collection = self::_collection($collection);

    if($n === 0) $result = array();
    elseif($n === 1 || is_null($n)) $result = array_pop($collection);
    else {
      $__ = new self;
      $result = $__->rest($collection, -$n);
    }

    return $result;
  }


  // Return a copy of the array with falsy values removed
  public static function compact($collection=null) {
    $collection = self::_collection($collection);

    $__ = new self;
    return $__->select($collection, function($val) {
      return (bool) $val;
    });
  }


  // Flattens a multidimensional array
  public static function flatten($collection=null, $shallow=null) {
    $collection = self::_collection($collection);

    $return = array();
    if(count($collection) > 0) {
      foreach($collection as $item) {
        if(is_array($item)) {
          $__ = new self;
          $return = array_merge($return, ($shallow) ? $item : $__->flatten($item));
        }
        else $return[] = $item;
      }
    }
    return $return;
  }


  // Returns a copy of the array with all instances of val removed
  public static function without($collection=null, $val=null) {
    $args = func_get_args();
    $collection = $args[0];
    $collection = self::_collection($collection);

    $num_args = count($args);
    if($num_args === 1) return $collection;
    if(count($collection) === 0) return $collection;

    $__ = new self;
    $removes = $__->rest($args);
    foreach($removes as $remove) {
      $remove_keys = array_keys($collection, $remove, true);
      if(count($remove_keys) > 0) {
        foreach($remove_keys as $key) {
          unset($collection[$key]);
        }
      }
    }
    return $collection;
  }


  // Return an array of the unique values
  // uniq alias: unique
  public static function unique($collection=null, $is_sorted=null, $iterator=null) { return self::uniq($collection, $is_sorted, $iterator); }
  public static function uniq($collection=null, $is_sorted=null, $iterator=null) {
    $collection = self::_collection($collection);

    $return = array();
    if(count($collection) === 0) return $return;

    $calculated = array();
    foreach($collection as $item) {
      $val = (!is_null($iterator)) ? $iterator($item) : $item;
      if(is_bool(array_search($val, $calculated, true))) {
        $calculated[] = $val;
        $return[] = $item;
      }
    }

    return $return;
  }


  // Returns an array containing the intersection of all the arrays
  public static function intersection($array=null) {
    $arrays = func_get_args();
    if(count($arrays) === 1) return $array;

    $__ = new self;
    $return = $__->first($arrays);
    foreach($__->rest($arrays) as $next) {
      if(!$__->isArray($next)) $next = str_split((string) $next);

      $return = array_intersect($return, $next);
    }

    return array_values($return);
  }


  // Merge together multiple arrays
  public static function union($array=null) {
    $arrays = func_get_args();

    $__ = new self;
    return $__->flatten(array_values(array_unique(call_user_func_array('array_merge', $arrays))));
  }


  // Get the difference between two arrays
  public static function difference($array_one=null, $array_two=null) {
    $arrays = func_get_args();

    return array_values(call_user_func_array('array_diff', $arrays));
  }


  // Get the index of the first match
  public static function indexOf($collection=null, $item=null) {
    $collection = self::_collection($collection);

    $key = array_search($item, $collection, true);
    return (is_bool($key)) ? -1 : $key;
  }


  // Get the index of the last match
  public static function lastIndexOf($collection=null, $item=null) {
    $collection = self::_collection($collection);

    krsort($collection);
    $__ = new self;
    return $__->indexOf($collection, $item);
  }


  // Returns an array of integers from start to stop (exclusive) by step
  public static function range($stop=null) {
    $args = func_get_args();

    $__ = new self;
    $args = $__->reject($args, function($val) {
      return is_null($val);
    });

    $num_args = count($args);
    switch($num_args) {
      case 1:
        list($start, $stop, $step) = array(0, $args[0], 1);
        break;
      case 2:
        list($start, $stop, $step) = array($args[0], $args[1], 1);
        if($stop < $start) return array();
        break;
      default:
        list($start, $stop, $step) = array($args[0], $args[1], $args[2]);
        if($step > 0 && $step > $stop) return array($start);
    }
    $results = range($start, $stop, $step);

    // Switch inclusive to exclusive
    if($step > 0 && $__->last($results) >= $stop) array_pop($results);
    elseif($step < 0 && $__->last($results) <= $stop) array_pop($results);

    return $results;
  }


  // Merges arrays
  public static function zip($array=null) {
    $arrays = func_get_args();
    $num_arrays = count($arrays);
    if($num_arrays === 1) return $array;

    $__ = new self;
    $num_return_arrays = $__->max($__->map($arrays, function($array) {
      return count($array);
    }));
    $return_arrays = $__->range($num_return_arrays);
    foreach($return_arrays as $k=>$v) {
      if(!is_array($return_arrays[$k])) $return_arrays[$k] = array();

      foreach($arrays as $a=>$array) {
        $return_arrays[$k][$a] = array_key_exists($k, $array) ? $array[$k] : null;
      }
    }

    return $return_arrays;
  }


  // Get the max value in the collection
  public static function max($collection=null, $iterator=null) {
    if(is_null($iterator)) return max($collection);

    $results = array();
    foreach($collection as $k=>$item) {
      $results[$k] = $iterator($item);
    }
    arsort($results);
    $__ = new self;
    $first_key = $__->first(array_keys($results));
    return $collection[$first_key];
  }


  // Get the min value in the collection
  public static function min($collection=null, $iterator=null) {
    if(is_null($iterator)) return min($collection);

    $results = array();
    foreach($collection as $k=>$item) {
      $results[$k] = $iterator($item);
    }
    asort($results);
    $__ = new self;
    $first_key = $__->first(array_keys($results));
    return $collection[$first_key];
  }


  // Sort the collection by return values from the iterator
  public static function sortBy($collection=null, $iterator=null) {
    $results = array();
    foreach($collection as $k=>$item) {
      $results[$k] = $iterator($item);
    }
    asort($results);
    foreach($results as $k=>$v) {
      $results[$k] = $collection[$k];
    }
    return array_values($results);
  }


  // Group the collection by return values from the iterator
  public static function groupBy($collection=null, $iterator=null) {
    $result = array();
    $collection = (array) $collection;
    foreach($collection as $k=>$v) {
      $key = (is_callable($iterator)) ? $iterator($v, $k) : $v[$iterator];
      if(!array_key_exists($key, $result)) $result[$key] = array();
      $result[$key][] = $v;
    }
    return $result;
  }


  // Returns the index at which the value should be inserted into the sorted collection
  public static function sortedIndex($collection=null, $value=null, $iterator=null) {
    $collection = (array) self::_collection($collection);
    $__ = new self;

    $calculated_value = (!is_null($iterator)) ? $iterator($value) : $value;

    while(count($collection) > 1) {
      $midpoint = floor(count($collection) / 2);
      $midpoint_values = array_slice($collection, $midpoint, 1);
      $midpoint_value = $midpoint_values[0];
      $midpoint_calculated_value = (!is_null($iterator)) ? $iterator($midpoint_value) : $midpoint_value;

      $collection = ($calculated_value < $midpoint_calculated_value) ? array_slice($collection, 0, $midpoint, true) : array_slice($collection, $midpoint, null, true);
    }
    $keys = array_keys($collection);

    return current($keys) + 1;
  }

  // Shuffle the array
  public static function shuffle($collection=null) {
    $collection = (array) self::_collection($collection);
    shuffle($collection);

    return $collection;
  }


  // Return the collection as an array
  public static function toArray($collection=null) {
    return (array) $collection;
  }


  // Get the collection's keys
  public static function keys($collection=null) {
    if(!is_object($collection) && !is_array($collection)) throw new Exception('Invalid object');

    return array_keys((array) $collection);
  }


  // Get the collection's values
  public static function values($collection=null) {
    return array_values((array) $collection);
  }


  // Copy all properties from the source objects into the destination object
  public static function extend($object=null) {
    $args = func_get_args();

    $num_args = func_num_args();
    if($num_args === 1) return $object;

    $is_object = is_object($object);
    $array = (array) $object;
    $__ = new self;
    $extensions = $__->rest(func_get_args());
    foreach($extensions as $extension) {
      $extension = (array) $extension;
      $array = array_merge($array, $extension);
    }
    return ($is_object) ? (object) $array : $array;
  }


  // Returns the object with any missing values filled in using the defaults.
  public static function defaults($object=null) {
    $args = func_get_args();
    list($object) = $args;

    $num_args = count($args);
    if($num_args === 1) return $object;

    $is_object = is_object($object);
    $array = (array) $object;
    $__ = new self;
    $extensions = $__->rest($args);
    foreach($extensions as $extension) {
      $extension = (array) $extension;
      $array = array_merge($extension, $array);
    }
    return ($is_object) ? (object) $array : $array;
  }


  // Get the names of functions available to the object
  // functions alias: methods
  public static function methods($object=null) { return self::functions($object); }
  public static function functions($object=null) {
    return get_class_methods(get_class($object));
  }


  // Returns a shallow copy of the object
  public static function clon(&$object=null) {
    $clone = null;
    if(is_array($object)) $clone = (array) clone (object) $object;
    elseif(!is_object($object)) $clone = $object;
    elseif(!$clone) $clone = clone $object;

    // shallow copy object
    if(is_object($clone) && count($clone) > 0) {
      foreach($clone as $k=>$v) {
        if(is_array($v) || is_object($v)) $clone->$k =& $object->$k;
      }
    }

    // shallow copy array
    elseif(is_array($clone) && count($clone) > 0) {
      foreach($clone as $k=>$v) {
        if(is_array($v) || is_object($v)) $clone[$k] =& $object[$k];
      }
    }
    return $clone;
  }


  // Invokes the interceptor on the object, then returns the object
  public static function tap($object=null, $interceptor=null) {
    $interceptor($object);
    return $object;
  }


  // Does the given key exist?
  public static function has($collection=null, $key=null) {
    $collection = (array) self::_collection($collection);

    return array_key_exists($key, $collection);
  }


  // Are these items equal?
  public static function isEqual($a=null, $b=null) {
    if($a === $b) return true;
    if(gettype($a) !== gettype($b)) return false;
    if(is_callable($a) !== is_callable($b)) return false;

    if($a == $b) return true;

    // Objects and arrays compared by values
    if(is_object($a) || is_array($a)) {

      // Do either implement isEqual()?
      if(is_object($a) && isset($a->isEqual)) return $a->isEqual($b);
      if(is_object($b) && isset($b->isEqual)) return $b->isEqual($a);
      if(is_array($a) && array_key_exists('isEqual', $a)) return $a['isEqual']($b);
      if(is_array($b) && array_key_exists('isEqual', $b)) return $b['isEqual']($a);

      if(count($a) !== count($b)) return false;

      $__ = new self;
      $keys_equal = $__->isEqual($__->keys($a), $__->keys($b));
      $values_equal = $__->isEqual($__->values($a), $__->values($b));
      return $keys_equal && $values_equal;
    }

    return false;
  }


  // Is this item empty?
  public static function isEmpty($item=null) {
    return is_array($item) || is_object($item) ? !((bool) count((array) $item)) : (!(bool) $item);
  }


  // Is this item an object?
  public static function isObject($item=null) {
    return is_object($item);
  }


  // Is this item an array?
  public static function isArray($item=null) {
    return is_array($item);
  }


  // Is this item a string?
  public static function isString($item=null) {
    return is_string($item);
  }


  // Is this item a number?
  public static function isNumber($item=null) {
    return (is_int($item) || is_float($item) && !is_nan($item) && !is_infinite($item));
  }


  // Is this item a bool?
  public static function isBoolean($item=null) {
    return is_bool($item);
  }


  // Is this item a function (by type, not by name)?
  public static function isFunction($item=null) {
    return is_object($item) && is_callable($item);
  }


  // Is this item an instance of DateTime?
  public static function isDate($item=null) {
    return is_object($item) && get_class($item) === 'DateTime';
  }


  // Is this item a NaN value?
  public static function isNaN($item=null) {
    return is_nan($item);
  }


  // Returns the same value passed as the argument
  public static function identity() {
    $args = func_get_args();

    if(count($args) > 0) return $args[0];

    return function($x) {
      return $x;
    };
  }


  // Generate a globally unique id, optionally prefixed
  public static $_uniqueId = -1;
  public static function uniqueId($prefix=null) {
    self::$_uniqueId++;

    return (is_null($prefix)) ? self::$_uniqueId : $prefix . self::$_uniqueId;
  }


  // Invokes the iterator n times
  public static function times($n=null, $iterator=null) {
    if(is_null($n)) $n = 0;

    for($i=0; $i<$n; $i++) $iterator($i);
    return null;
  }


  // Temporary PHP open and close tags used within templates
  // Allows for normal processing of templates even when
  // the developer uses PHP open or close tags for interpolation or evaluation
  const TEMPLATE_OPEN_TAG = '760e7dab2836853c63805033e514668301fa9c47';
  const TEMPLATE_CLOSE_TAG= 'd228a8fa36bd7db108b01eddfb03a30899987a2b';

  const TEMPLATE_DEFAULT_EVALUATE   = '/<%([\s\S]+?)%>/';
  const TEMPLATE_DEFAULT_INTERPOLATE= '/<%=([\s\S]+?)%>/';
  const TEMPLATE_DEFAULT_ESCAPE     = '/<%-([\s\S]+?)%>/';
  public static $_template_settings = array(
    'evaluate'    => self::TEMPLATE_DEFAULT_EVALUATE,
    'interpolate' => self::TEMPLATE_DEFAULT_INTERPOLATE,
    'escape'      => self::TEMPLATE_DEFAULT_ESCAPE
  );

  // Set template settings
  public static function templateSettings($settings=null) {
    $_template_settings =& self::$_template_settings;

    if(is_null($settings)) {
      $_template_settings = array(
        'evaluate'    => self::TEMPLATE_DEFAULT_EVALUATE,
        'interpolate' => self::TEMPLATE_DEFAULT_INTERPOLATE,
        'escape'      => self::TEMPLATE_DEFAULT_ESCAPE
      );
      return true;
    }

    foreach($settings as $k=>$v) {
      if(!array_key_exists($k, $_template_settings)) continue;

      $_template_settings[$k] = $v;
    }
    return true;
  }


  // Compile templates into functions that can be evaluated for rendering
  public static function template($code=null, $context=null) {
    $class_name = __CLASS__;

    $return = function($context=null) use ($code, $class_name) {
      $ts = $class_name::$_template_settings;

      // Wrap escaped, interpolated, and evaluated blocks inside PHP tags
      extract((array) $context);
      preg_match_all($ts['escape'], $code, $vars, PREG_SET_ORDER);
      if(count($vars) > 0) {
        foreach($vars as $var) {
          $echo = $class_name::TEMPLATE_OPEN_TAG . ' echo htmlentities(' . trim($var[1]) . '); ' . $class_name::TEMPLATE_CLOSE_TAG;
          $code = str_replace($var[0], $echo, $code);
        }
      }
      preg_match_all($ts['interpolate'], $code, $vars, PREG_SET_ORDER);
      if(count($vars) > 0) {
        foreach($vars as $var) {
          $echo = $class_name::TEMPLATE_OPEN_TAG . ' echo ' . trim($var[1]) . '; ' . $class_name::TEMPLATE_CLOSE_TAG;
          $code = str_replace($var[0], $echo, $code);
        }
      }
      preg_match_all($ts['evaluate'], $code, $vars, PREG_SET_ORDER);
      if(count($vars) > 0) {
        foreach($vars as $var) {
          $echo = $class_name::TEMPLATE_OPEN_TAG . trim($var[1]) . $class_name::TEMPLATE_CLOSE_TAG;
          $code = str_replace($var[0], $echo, $code);
        }
      }
      $code = str_replace($class_name::TEMPLATE_OPEN_TAG, '<?php ', $code);
      $code = str_replace($class_name::TEMPLATE_CLOSE_TAG, '?>', $code);

      // Use the output buffer to grab the return value
      $code = 'ob_start(); extract($context); ?>' . $code . '<?php return ob_get_clean();';

      $func = create_function('$context', $code);
      return $func((array) $context);
    };

    // Return function or call function depending on context
    return ((isset($this) && isset($this->_wrapped) && $this->_wrapped) || !is_null($context) ? $return($context) : $return);
  }

  // Escape
  public static function escape($item=null) {
    return htmlentities($item);
  }


  // Memoizes a function by caching the computed result.
  public static $_memoized = array();
  public static function memoize($function=null, $hashFunction=null) {
    $class_name = __CLASS__;
    return function() use ($function, $class_name, $hashFunction) {

      // Generate a key based on hashFunction
      $args = func_get_args();
      if(is_null($hashFunction)) $hashFunction = function($function, $args) {

        // Try using var_export to identify the function
        return md5(join('_', array(
          var_export($function, true),
          var_export($args, true)
        )));
      };
      $key = $hashFunction($function, $args);

      if(!array_key_exists($key, $class_name::$_memoized)) {
        $class_name::$_memoized[$key] = call_user_func_array($function, $args);
      }
      return $class_name::$_memoized[$key];
    };
  }


  // Throttles a function so that it can only be called once every wait milliseconds
  public static $_throttled = array();
  public static function throttle($function=null, $wait=null) {
    $class_name = __CLASS__;
    return function() use ($function, $wait, $class_name) {

      // Try using var_export to identify the function
      $key = md5(join('', array(
        var_export($function, true),
        $wait
      )));

      $microtime = microtime(true);
      $ready_to_call = (!array_key_exists($key, $class_name::$_throttled) || $microtime >= $class_name::$_throttled[$key]);
      if($ready_to_call) {
        $next_callable_time = $microtime + ($wait / 1000);
        $class_name::$_throttled[$key] = $next_callable_time;
        return call_user_func_array($function, func_get_args());
      }
    };
  }


  // Creates a version of the function that can only be called once
  public static $_onced = array();
  public static function once($function=null) {
    $class_name = __CLASS__;

    return function() use ($function, $class_name) {

      // Try using var_export to identify the function
      $key = md5(var_export($function, true));
      if(!array_key_exists($key, $class_name::$_onced)) {
        $class_name::$_onced[$key] = call_user_func_array($function, func_get_args());
      }

      return $class_name::$_onced[$key];
    };
  }


  // Wraps the function inside the wrapper function, passing it as the first argument
  public static function wrap($function=null, $wrapper=null) {
    return function() use ($wrapper, $function) {
      $args = array_merge(array($function), func_get_args());
      return call_user_func_array($wrapper, $args);
    };
  }


  // Returns the composition of the functions
  public static function compose() {
    $functions = func_get_args();

    return function() use ($functions) {
      $args = func_get_args();
      foreach($functions as $function) {
        $args[0] = call_user_func_array($function, $args);
      }
      return $args[0];
    };
  }


  // Creates a version of the function that will only run after being called count times
  public static $_aftered = array();
  public static function after($count=null, $function=null) {
    $class_name = __CLASS__;
    $key = md5(mt_rand());

    $func = function() use ($function, $class_name, $count, $key) {
      if(!array_key_exists($key, $class_name::$_aftered)) $class_name::$_aftered[$key] = 0;
      $class_name::$_aftered[$key] += 1;

      if($class_name::$_aftered[$key] >= $count) return call_user_func_array($function, func_get_args());
    };
    return ($count) ? $func : $func();
  }


  // Get a collection in a way that supports strings
  private static function _collection($collection) {
    return (!is_array($collection) && !is_object($collection)) ? str_split((string) $collection) : $collection;
  }
}