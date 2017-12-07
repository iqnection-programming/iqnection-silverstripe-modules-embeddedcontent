<?php

use SilverStripe\View\Parsers\ShortcodeParser;
ShortcodeParser::get('default')->register('embed_content', array('EmbeddedContent', 'ParseShortCode'));
