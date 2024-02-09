<?php

class Parsedown
{
    public function text($text)
    {
        // Convert Markdown text to HTML
        // This is a simplified example, the actual implementation is more complex
        $html = str_replace("\n", "<br>", $text);
        $html = preg_replace("/\*(.*?)\*/", "<em>$1</em>", $html);
        $html = preg_replace("/\*\*(.*?)\*\*/", "<strong>$1</strong>", $html);
        $html = preg_replace("/\#\#(.*?)\#\#/", "<h2>$1</h2>", $html);
        $html = preg_replace("/\#(.*?)\#/", "<h1>$1</h1>", $html);
        return $html;
    }
}