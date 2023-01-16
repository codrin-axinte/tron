<?php

namespace App\Nova\Traits;

use Manogi\Tiptap\Tiptap;

trait HasNovaContent
{
    protected function contentField($name = 'content', $label = null): Tiptap
    {
        return Tiptap::make($label, $name)
            ->buttons([
                'heading',
                '|',
                'italic',
                'bold',
                '|',
                'link',
                'strike',
                'underline',
                'highlight',
                '|',
                'bulletList',
                'orderedList',
                'br',
                'blockquote',
                '|',
                'horizontalRule',
                'hardBreak',
                '|',
                'table',
                '|',
                'image',
                '|',
                'textAlign',
                '|',
                'rtl',
                '|',
                'history',
                '|',
                'editHtml',
            ]);
    }
}
