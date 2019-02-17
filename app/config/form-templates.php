<?php

return [
    'input' => '<input class="form-control" type="{{type}}" name="{{name}}"{{attrs}}/>',
    'inputContainer' => '<div class="form-group"><div class="input {{type}}{{required}}">{{content}}</div></div>',
    'inputContainerError' => '<div class="input {{type}}{{required}} error">{{content}}{{error}}</div>',
    'textarea' => '<textarea class="form-control" name="{{name}}"{{attrs}}>{{value}}</textarea>',
];
