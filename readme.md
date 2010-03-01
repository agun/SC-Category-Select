SC Category Select 1.1.2
================

Author: [Andrew Gunstone][1] ([Email][2]) - [Thirst Studios][3]

SC Category Select is a [Pixel&Tonic's FieldFrame][4] FieldType which displays selected category options in a drop-down list. It only allows selection of a single category. You can select multiple category groups. The FieldType respects sub-categories.

The `category id` is saved - not the category name - however you can display the heading, description and url_title using some custom functions.  See "Usage" below for details.

On saving an entry, the 'real' category will be automatically selected as well, for ALL SC Category Select fields listed on the publish form... even those in a FF Matrix.  This allows template interaction with the fieldtype tag, and the use of normal category tags. It also has the added benefit of allowing you to use the category search in the CP Edit tab.

It is recommended that you 'hide' the categories tab when using this FieldType, as the add-on overwrites category selections for the entry on save.

Version
------------
###1.1.2

* Fixed a bug when saving with no category selected
* Added some LG Addon Updater goodness

###1.1

* Now FieldFrame Matrix compatible
* Now EE Multiple Site Manager (MSM) compatible
* Fixed a bug where only 1 category was saved to categories table even if multiple SC Category Select fields were used on the publish form
* Fixed some minor bugs
* Thanks to Mike Gallagher for his help with testing

###1.0 

* Initial release

Requirements
------------

SC Category Select requires [ExpressionEngine 1.6.8+][5]. This FieldType does not work in EE2.0.

SC Cateogry Select requires [Pixel&Tonic's FieldFrame 1.4][4] extension.

Installation
------------

The SC Category Select FieldType contains an extension and language file:

* [Download][6] the latest version of SC Category Select
* Extract the .zip file to your desktop
* Copy `system/extensions/fieldtypes/sc_category_select` to `/system/extensions/fieldtypes`
* Copy the `language/english/lang.sc_category_select.php` file to `/system/languages/english`
* Open the FieldTypes Manager
* Enable the SC Category Select FieldType
* The FieldType will now be available when creating a Custom Weblog Fields

Usage
-----

### Creating your fieldtype

Using this FieldType is easy.

Create a new Weblog Custom Field as usual. Select SC Category Select from the Field Type dropdown.

Select one or more Category Groups from the multi-select list.

You can also use this field type with FieldFrame Matrix.

Finish entering all other details for the Custom Field and save it!

### Using the fieldtype in your template

To display the category id, use the custom field tag as normal for the SC Cateogry Select field:

	<p>The selected category id is {my_custom_sc_category_select_field}.</p>

You can also display the category heading, description and url_title using (will return blank if nothing exists):

	<p>Category heading - {my_custom_sc_category_select_field:heading}.</p>
	<p>Category description - {my_custom_sc_category_select_field:description}.</p>
	<p>Category url_title - {my_custom_sc_category_select_field:url_title}.</p>
	
### Please note!

To use the normal `{exp:weblog:categories}` tags, you must have added the appropriate category groups to the weblog you are using.

License
-------

Ownership of this software always remains property of the author.

You may:

* Modify the software for your own projects
* Use the software on personal and commercial projects

You may not:

* Resell or redistribute the software in any form or manner without permission of the author
* Remove the license / copyright / author credits

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

[1]: http://sassafrasconsulting.com.au "Authors personal website"
[2]: mailto:andrew@thirststudios.com "Authors email"
[3]: http://thirststudios.com "ExpressionEngine web design and development"
[4]: http://pixelandtonic.com/fieldframe "Pixel&Tonic FieldFrame"
[5]: http://expressionengine.com/?affiliate=newism "ExpressionEngine"
[6]: http://sassafrasconsulting.com.au/software/category-select/ "SC Category Select"
<!-- 
    This document is marked up using the Markdown syntax: http://daringfireball.net/projects/markdown/
    If you are reading this notice you may need to run the raw content through the Dingus online Markdown parser: http://daringfireball.net/projects/markdown/dingus
    If you are viewing this readme on Github you don't need to re-parse the file.
-->