# PHP-Textile CLI
# https://github.com/gocom/php-textile-cli
#
# Copyright (C) 2025 Jukka Svahn
#
# Permission is hereby granted, free of charge, to any person obtaining a
# copy of this software and associated documentation files (the "Software"),
# to deal in the Software without restriction, including without limitation
# the rights to use, copy, modify, merge, publish, distribute, sublicense,
# and/or sell copies of the Software, and to permit persons to whom the
# Software is furnished to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.

complete -c textile -o h -l help -d "Print help"
complete -c textile -o V -l version -d "Print version"
complete -c textile -o t -l document-type -r -a "html5 xhtml" -d "Document type"
complete -c textile -o d -l document-root-directory -r -F -d "Set document root directory"
complete -c textile -o l -l lite -d "Enable lite mode"
complete -c textile -o i -l no-images -d "Disable images"
complete -c textile -o L -l link-relationship -r -a "nofollow noreferrer" -d "Set link relationship"
complete -c textile -o r -l restricted -d "Enable restricted mode"
complete -c textile -o U -l raw-blocks -d "Enable raw blocks"
complete -c textile -o A -l align-classes -d "Enable alignment classes"
complete -c textile -o a -l no-align-classes -d "Disable alignment classes"
complete -c textile -o b -l no-block-tags -d "Disable block tags"
complete -c textile -o w -l no-line-wrap -d "Disable line wrapping"
complete -c textile -o p -l image-prefix -d "Set image URL prefix"
complete -c textile -o P -l link-prefix -d "Set link URL prefix"
complete -c textile -o z -l no-dimensions -d "Disable adding width and height to images"
complete -c textile -o o -l output -r -F -d "Saved output HTML file"
complete -c textile -o q -l quiet -d "Do not output any message"
complete -c textile -l ansi -d "Force enable ANSI output"
complete -c textile -l no-ansi -d "Disable ANSI output"
complete -c textile -l no-interaction -d "Do not ask any interactive question"
complete -c textile -o v -l verbose -d "Increase verbosity"
