<?php
// src/Core/Flash.php
namespace App\Core{
	class Flash {
	    public static function set($key, $message) {
	        $_SESSION['_flash'][$key] = $message;
	    }

	    public static function get($key) {
	        $message = $_SESSION['_flash'][$key] ?? null;
	        unset($_SESSION['_flash'][$key]); // only available once
	        return $message;
	    }

	    public static function has($key) {
	        return isset($_SESSION['_flash'][$key]);
	    }
	}	
};

namespace {
	function set_flash($key, $message){
		return App\Core\Flash::set($key, $message);
	}
	function get_flash($key){
		return App\Core\Flash::get($key);
	}
	function has_flash($key){
		return App\Core\Flash::has($key);
	}

	function flash_message($type, $messages) {
	    $styles = [
	        'success' => 'bg-green-100 border-green-400 text-green-700',
	        'error'   => 'bg-red-100 border-red-400 text-red-700',
	        'warning' => 'bg-yellow-100 border-yellow-400 text-yellow-700',
	        'info'    => 'bg-blue-100 border-blue-400 text-blue-700',
	    ];

	    $css = $styles[$type] ?? $styles['info'];

	    echo "<div class=\"border px-4 py-3 rounded mb-4 {$css}\" role=\"alert\">";

	    if (is_array($messages)) {
	        echo "<ul class=\"list-disc list-inside text-sm\">";
	        foreach ($messages as $msgGroup) {
	            foreach ((array) $msgGroup as $msg) {
	                echo "<li>" . htmlspecialchars($msg) . "</li>";
	            }
	        }
	        echo "</ul>";
	    } else {
	        echo htmlspecialchars($messages);
	    }

	    echo "</div>";
	}

}
