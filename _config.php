<?php

use SilverStripe\View\Parsers\ShortcodeParser;
ShortcodeParser::get('default')->register('embed_content', array(IQnection\EmbeddedContent\EmbeddedContent::class, 'ParseShortCode'));
