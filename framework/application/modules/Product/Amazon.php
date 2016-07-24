<?php

# Get the Product Class
require_once Utility::locateFile(MODULES.'Product'.DS.'Product.php');

if(!defined('__PHP_SHA256_NANO_'))
{
	define('__PHP_SHA256_NANO_', true);

	/*
	 * Amazon
	 *
	 * The Amazon Class is used access and maintain the Amazon specific data in the `product` table in the database and interface with the Amazon.com API. For more information on accessing Amazon.com, see http://aws.amazon.com/
	 *
	 */
	class Amazon extends Product
	{
		/*** Class constants ***/

		const REQUEST_URL=PROTOCAL.'webservices.amazon.com/onca/xml?';
		const IMAGE_URL_PREFIX='http://ecx.';
		const IMAGE_URL_PREFIX_SSL='https://images-na.ssl-';

		/*** End Class constants ***/


		/*** data members ***/

		private $access_key;
		private $ass_tag;
		private $detailed_page_url=NULL;
		private $editorial_review=NULL;
		private $image_height=NULL;
		private $image_width=NULL;
		private $image_url=NULL;
		private $image_original_url=NULL;
		private $parsed_xml=NULL;
		private $secret_key;

		/*** End data members ***/



		/*** magic methods ***/

		/*
		 * __construct
		 *
		 * @access	public
		 */
		public function __construct($access_key, $ass_tag, $secret_key)
		{
			$this->setAccessKey($access_key);
			$this->setAssTag($ass_tag);
			$this->setSecretKey($secret_key);
		} #==== End -- __construct

		/*** End magic methods ***/



		/*** mutator methods ***/

		/*
		 * setAccessKey
		 *
		 * Sets the data member $access_key
		 *
		 * @param		$access_key
		 * @access	private
		 */
		private function setAccessKey($access_key)
		{
			$this->access_key=$access_key;
		} #==== End -- setAccessKey

		/*
		 * setAssTag
		 *
		 * Sets the data member $ass_tag
		 *
		 * @param		$ass_tag
		 * @access	private
		 */
		private function setAssTag($ass_tag)
		{
			$this->ass_tag=$ass_tag;
		} #==== End -- setAssTag

		/*
		 * setDetailedPageURL
		 *
		 * Sets the data member $detailed_page_url.
		 *
		 * @param		$detailed_page_url
		 * @access	protected
		 */
		protected function setDetailedPageURL($detailed_page_url)
		{
			# Check if the passed value is empty.
			if(!empty($detailed_page_url))
			{
				# Clean it up.
				$detailed_page_url=trim($detailed_page_url);
				# Set the data member.
				$this->detailed_page_url=$detailed_page_url;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->detailed_page_url=NULL;
			}
		} #==== End -- setDetailedPageURL

		/*
		 * setEditorialReview
		 *
		 * Sets the data member $editorial_review.
		 *
		 * @param		$editorial_review
		 * @access	protected
		 */
		protected function setEditorialReview($editorial_review)
		{
			# Check if the passed value is empty.
			if(!empty($editorial_review))
			{
				# Clean it up.
				$editorial_review=trim($editorial_review);
				# Set the data member.
				$this->editorial_review=$editorial_review;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->editorial_review=NULL;
			}
		} #==== End -- setEditorialReview

		/*
		 * setImageHeight
		 *
		 * Sets the data member $image_height.
		 *
		 * @param		$image_height
		 * @access	protected
		 */
		protected function setImageHeight($image_height)
		{
			# Check if the passed value is empty.
			if(!empty($image_height))
			{
				# Clean it up.
				$image_height=trim($image_height);
				# Set the data member.
				$this->image_height=$image_height;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->image_height=NULL;
			}
		} #==== End -- setImageHeight

		/*
		 * setImageWidth
		 *
		 * Sets the data member $image_width.
		 *
		 * @param		$image_width
		 * @access	protected
		 */
		protected function setImageWidth($image_width)
		{
			# Check if the passed value is empty.
			if(!empty($image_width))
			{
				# Clean it up.
				$image_width=trim($image_width);
				# Set the data member.
				$this->image_width=$image_width;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->image_width=NULL;
			}
		} #==== End -- setImageWidth

		/*
		 * setImageURL
		 *
		 * Sets the data member $image_url.
		 *
		 * @param		$image_url
		 * @access	protected
		 */
		protected function setImageURL($image_url)
		{
			# Check if the passed value is empty.
			if(!empty($image_url))
			{
				# Clean it up.
				$image_url=trim($image_url);
				# Set the data member.
				$this->image_url=$image_url;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->image_url=NULL;
			}
		} #==== End -- setImageURL

		/*
		 * setImageOriginalURL
		 *
		 * Sets the data member $image_original_url.
		 *
		 * @param		$original_url
		 * @access	protected
		 */
		protected function setImageOriginalURL($original_url)
		{
			# Check if the passed value is empty.
			if(!empty($original_url))
			{
				# Clean it up.
				$original_url=trim($original_url);
				# Set the data member.
				$this->image_original_url=$original_url;
			}
			else
			{
				# Explicitly set the data member to NULL.
				$this->image_original_url=NULL;
			}
		} #==== End -- setImageOriginalURL

		/*
		 * setParsedXML
		 *
		 * Sets the data member $parsed_xml
		 *
		 * @param		$parsed_xml
		 * @access	private
		 */
		private function setParsedXML($parsed_xml)
		{
			$this->parsed_xml=$parsed_xml;
		} #==== End -- setParsedXML

		/*
		 * setSecretKey
		 *
		 * Sets the data member $secret_key
		 *
		 * @param		$secret_key
		 * @access	private
		 */
		private function setSecretKey($secret_key)
		{
			$this->secret_key=$secret_key;
		} #==== End -- setSecretKey

		/*** End mutator methods ***/



		/*** accessor methods ***/

		/*
		 * getAccessKey
		 *
		 * Returns the data member $access_key.
		 *
		 * @access	protected
		 */
		protected function getAccessKey()
		{
			return $this->access_key;
		} #==== End -- getAccessKey

		/*
		 * getAssTag
		 *
		 * Returns the data member $ass_tag.
		 *
		 * @access	protected
		 */
		protected function getAssTag()
		{
			return $this->ass_tag;
		} #==== End -- getAssTag

		/*
		 * getDetailedPageURL
		 *
		 * Returns the data member $detailed_page_url.
		 *
		 * @access	protected
		 */
		protected function getDetailedPageURL()
		{
			return $this->detailed_page_url;
		} #==== End -- getDetailedPageURL

		/*
		 * getEditorialReview
		 *
		 * Returns the data member $editorial_review.
		 *
		 * @access	protected
		 */
		protected function getEditorialReview()
		{
			return $this->editorial_review;
		} #==== End -- getEditorialReview

		/*
		 * getImageHeight
		 *
		 * Returns the data member $image_height.
		 *
		 * @access	protected
		 */
		protected function getImageHeight()
		{
			return $this->image_height;
		} #==== End -- getImageHeight

		/*
		 * getImageWidth
		 *
		 * Returns the data member $image_width.
		 *
		 * @access	protected
		 */
		protected function getImageWidth()
		{
			return $this->image_width;
		} #==== End -- getImageWidth

		/*
		 * getImageURL
		 *
		 * Returns the data member $image_url.
		 *
		 * @access	protected
		 */
		protected function getImageURL()
		{
			return $this->image_url;
		} #==== End -- getImageURL

		/*
		 * getImageOriginalURL
		 *
		 * Returns the data member $image_original_url.
		 *
		 * @access	protected
		 */
		protected function getImageOriginalURL()
		{
			return $this->image_original_url;
		} #==== End -- getImageOriginalURL

		/*
		 * getParsedXML
		 *
		 * Returns the data member $parsed_xml.
		 *
		 * @access	protected
		 */
		protected function getParsedXML()
		{
			return $this->parsed_xml;
		} #==== End -- getParsedXML

		/*
		 * getSecretKey
		 *
		 * Returns the data member $secret_key.
		 *
		 * @access	protected
		 */
		protected function getSecretKey()
		{
			return $this->secret_key;
		} #==== End -- getSecretKey

		/*** End accessor methods ***/



		/*** public methods ***/

		/*
		 * displayAmazonProduct
		 *
		 * Creates Amazon product XHTML elements and sets them to an array for display.
		 *
		 * @param	array $asins		An array of product ASIN's.
		 * @param	$page
		 * @param	$identifier
		 * @param $option				An Array of various options for displaying Products. The available options are:
		 *												"image_size" => What size of image should be called from Amazon for Amazon products. the default is
		 *																				"MediumImage". The possible values are:
		 *																				"LargeImage"
		 *																				"MediumImage"
		 *																				"SmallImage"
		 *																				"SwatchImage"
		 *																				"ThumbnailImage"
		 *																				"TinyImage"
		 *												"max_char" => The maximum number of characters to display in product descriptions. The default is NULL
		 *																			(unlimited).
		 *												"access_level" => The access levels needed for a logged in User to modify the products - must be a
		 *																					space sepparated string of numbers. The default is ADMIN_USERS.
		 *												"labels" => TRUE if other buttons should be displayed (ie "download", "more") FALSE if not. The default is
		 *																		TRUE.
		 *												"title_class" => The class for the title container. Default is "title".
		 *												"title_link" => NULL if the title should NOT be wrapped in an anchor tag. Otherwise, the URL to link to. If
		 *																				the title should link to the details page, simply pass the value "default". Default is
		 *																				"default".
		 *												"title_link_title" => This is NOT used if the title should NOT be wrapped in an anchor tag. If it WILL be,
		 *																							pass the text to be used in the "title" attribute for the link. To use the product
		 *																							title, "{PRODUCT_TITLE}", simply pass the value "default". The default is "default".
		 * @access	public
		 */
		public function displayAmazonProduct($asins, $page, $identifier, $options=array())
		{
			# Bring the Login object into scope.
			global $login;

			try
			{
				# Get the Amazon product and create the cache.
				$this->getAmazonProduct($page, $identifier, $asins);
				# Set the parsed xml to a variable.
				$parsed_xml=$this->getParsedXML();
				# Check if there is Amazon product to display.
				if(!empty($parsed_xml->Items->Item))
				{
					# General options defaults.
					$access_level=((array_key_exists('access_level', $options)) ? $options['access_level'] : ADMIN_USERS);
					$labels=((array_key_exists('labels', $options)) ? $options['labels'] : TRUE);
					$label_class=((array_key_exists('label_class', $options)) ? $options['label_class'] : 'label');
					$max_char=((array_key_exists('max_char', $options)) ? $options['max_char'] : NULL);

					# Set the User's ability to edit this product to FALSE as default.
					$edit=FALSE;
					# Set the User's ability to delete this product to FALSE as default.
					$delete=FALSE;
					# Check if the logged in User has the privileges to modify this product.
					if($login->checkAccess($access_level)===TRUE)
					{
						# Set the User's ability to modify this product. Default is TRUE.
						$edit=((array_key_exists('edit', $options)) ? $options['edit'] : TRUE);
						$edit_class=((array_key_exists('edit_class', $options)) ? $options['edit_class'] : 'edit');
						$edit_link_title=((array_key_exists('edit_link_title', $options)) ? $options['edit_link_title'] : 'Edit this product');
						$edit_value=((array_key_exists('edit_value', $options)) ? $options['edit_value'] : 'Edit');
						$delete=((array_key_exists('delete', $options)) ? $options['delete'] : TRUE);
						$delete_class=((array_key_exists('delete_class', $options)) ? $options['delete_class'] : 'delete');
						$delete_link_title=((array_key_exists('delete_link_title', $options)) ? $options['delete_link_title'] : 'Delete this product');
						$delete_value=((array_key_exists('delete_value', $options)) ? $options['delete_value'] : 'Delete');
					}

					# Author options defaults.
					$author_class=((array_key_exists('author_class', $options)) ? $options['author_class'] : 'author');
					$author_label=((array_key_exists('author_label', $options)) ? $options['author_label'] : (($labels===TRUE) ? 'Author:' : ''));
					$author_label_class=((array_key_exists('author_label_class', $options)) ? $options['author_label_class'] : $label_class);
					$author_link=((array_key_exists('author_link', $options)) ? $options['author_link'] : NULL);
					$author_link_title=((array_key_exists('author_link_title', $options)) ? $options['author_link_title'] : 'default');

					# Buy button options defaults.
					$buy_amazon_button_class=((array_key_exists('buy_amazon_button_class', $options)) ? $options['buy_amazon_button_class'] : 'button-amazon');
					$buy_amazon_button_name=((array_key_exists('buy_amazon_button_name', $options)) ? $options['buy_amazon_button_name'] : 'add_cart');
					$buy_amazon_label=((array_key_exists('buy_amazon_label', $options)) ? $options['buy_amazon_label'] : (($labels===TRUE) ? 'Buy now from' : ''));
					$buy_amazon_label_class=((array_key_exists('buy_amazon_label_class', $options)) ? $options['buy_amazon_label_class'] : $label_class);
					$buy_amazon_submit_class=((array_key_exists('buy_amazon_submit_class', $options)) ? $options['buy_amazon_submit_class'] : 'submit-amazon');
					$buy_amazon_submit_image=((array_key_exists('buy_amazon_submit_image', $options)) ? $options['buy_amazon_submit_image'] : NULL);
					$buy_amazon_submit_name=((array_key_exists('buy_amazon_submit_name', $options)) ? $options['buy_amazon_submit_name'] : 'add');
					$buy_amazon_submit_type=((array_key_exists('buy_amazon_submit_type', $options)) ? $options['buy_amazon_submit_type'] : 'submit');
					$buy_amazon_submit_value=((array_key_exists('buy_amazon_submit_value', $options)) ? $options['buy_amazon_submit_value'] : 'Buy from Amazon');
					$buy_class=((array_key_exists('buy_class', $options)) ? $options['buy_class'] : 'buy');
					$buy_link_title=((array_key_exists('buy_paypal_value', $options)) ? $options['buy_paypal_value'] : 'default');
					$buy_link_value=((array_key_exists('buy_link_value', $options)) ? $options['buy_link_value'] : 'Buy Now');
					$buy_paypal_button_class=((array_key_exists('buy_paypal_button_class', $options)) ? $options['buy_paypal_button_class'] : 'button-amazon');
					$buy_paypal_button_name=((array_key_exists('buy_paypal_button_name', $options)) ? $options['buy_paypal_button_name'] : 'add_cart');
					$buy_paypal_submit_class=((array_key_exists('buy_paypal_submit_class', $options)) ? $options['buy_paypal_submit_class'] : 'submit-paypal');
					$buy_paypal_submit_image=((array_key_exists('buy_paypal_image', $options)) ? $options['buy_paypal_image'] : NULL);
					$buy_paypal_submit_name=((array_key_exists('buy_paypal_name', $options)) ? $options['buy_paypal_name'] : 'submit');
					$buy_paypal_submit_type=((array_key_exists('buy_paypal_type', $options)) ? $options['buy_paypal_type'] : 'submit');
					$buy_paypal_submit_value=((array_key_exists('buy_paypal_value', $options)) ? $options['buy_paypal_value'] : $buy_link_value);

					# Content options defaults.
					$content_class=((array_key_exists('content_class', $options)) ? $options['content_class'] : 'content cont');
					$content_description_as_one=((array_key_exists('content_description_as_one', $options)) ? $options['content_description_as_one'] : FALSE);
					$content_label=((array_key_exists('content_label', $options)) ? $options['content_label'] : '');
					$content_label_class=((array_key_exists('content_label_class', $options)) ? $options['content_label_class'] : $label_class);
					$content_max_char=((array_key_exists('content_max_char', $options)) ? $options['content_max_char'] : $max_char);
					$content_more_class=((array_key_exists('content_more_class', $options)) ? $options['content_more_class'] : 'more');

					# Description options defaults.
					$description_class=((array_key_exists('description_class', $options)) ? $options['description_class'] : 'content desc');
					$description_label=((array_key_exists('description_label', $options)) ? $options['description_label'] : (($labels===TRUE) ? 'Description:' : ''));
					$description_label_class=((array_key_exists('description_label_class', $options)) ? $options['description_label_class'] : $label_class);
					$description_max_char=((array_key_exists('description_max_char', $options)) ? $options['description_max_char'] : $max_char);
					$description_more_class=((array_key_exists('description_more_class', $options)) ? $options['description_more_class'] : 'more');

					# File options defaults.
					$file_download_class=((array_key_exists('file_download_class', $options)) ? $options['file_download_class'] : 'download');
					$file_download_link_title=((array_key_exists('file_download_link_title', $options)) ? $options['file_download_link_title'] : 'Download Now!');
					$file_download_link_value=((array_key_exists('file_download_link_value', $options)) ? $options['file_download_link_value'] : 'Download');
					$file_name_class=((array_key_exists('file_name_class', $options)) ? $options['file_name_class'] : 'file-name');
					$file_name_label=((array_key_exists('file_name_label', $options)) ? $options['file_name_label'] : (($labels===TRUE) ? 'Name:' : ''));
					$file_name_label_class=((array_key_exists('file_name_label_class', $options)) ? $options['file_name_label_class'] : $label_class);
					$file_name_link=((array_key_exists('file_name_link', $options)) ? $options['file_name_link'] : TRUE);
					$file_name_link_title=((array_key_exists('file_name_link_title', $options)) ? $options['file_name_link_title'] : 'default');
					$file_title_class=((array_key_exists('file_title_class', $options)) ? $options['file_title_class'] : 'file-title');
					$file_title_label=((array_key_exists('file_title_label', $options)) ? $options['file_title_label'] : (($labels===TRUE) ? 'Title:' : ''));
					$file_title_label_class=((array_key_exists('file_title_label_class', $options)) ? $options['file_title_label_class'] : $label_class);
					$file_title_link=((array_key_exists('file_title_link', $options)) ? $options['file_title_link'] : FALSE);
					$file_title_link_title=((array_key_exists('file_title_link_title', $options)) ? $options['file_title_link_title'] : 'default');

					# Image options defaults.
					$image_link=((array_key_exists('image_link', $options)) ? $options['image_link'] : FW_POPUP_HANDLE);
					$image_size=((array_key_exists('image_size', $options)) ? $options['image_size'] : 'MediumImage');

					# Publisher options defaults.
					$publisher_class=((array_key_exists('publisher_class', $options)) ? $options['publisher_class'] : 'publisher');
					$publisher_label=((array_key_exists('publisher_label', $options)) ? $options['publisher_label'] : (($labels===TRUE) ? 'Publisher:' : ''));
					$publisher_label_class=((array_key_exists('publisher_label_class', $options)) ? $options['publisher_label_class'] : $label_class);
					$publisher_link=((array_key_exists('publisher_link', $options)) ? $options['publisher_link'] : 'default');
					$publisher_link_title=((array_key_exists('publisher_link_title', $options)) ? $options['publisher_link_title'] : 'default');

					# Price options defaults.
					$price_class=((array_key_exists('price_class', $options)) ? $options['price_class'] : 'price');
					$price_label=((array_key_exists('price_label', $options)) ? $options['price_label'] : (($labels===TRUE) ? 'Price:' : ''));
					$price_label_class=((array_key_exists('price_label_class', $options)) ? $options['price_label_class'] : $label_class);
					$price_link=((array_key_exists('price_link', $options)) ? $options['price_link'] : 'default');
					$price_link_title=((array_key_exists('price_link_title', $options)) ? $options['price_link_title'] : 'default');

					# Title options defaults.
					$title_class=((array_key_exists('title_class', $options)) ? $options['title_class'] : 'title');
					$title_label=((array_key_exists('title_label', $options)) ? $options['title_label'] : '');
					$title_label_class=((array_key_exists('title_label_class', $options)) ? $options['title_label_class'] : $label_class);
					$title_link=((array_key_exists('title_link', $options)) ? $options['title_link'] : 'default');
					$title_link_title=((array_key_exists('title_link_title', $options)) ? $options['title_link_title'] : 'default');

					# Create an empty array to hold Amazon ASIN's after that record has been added to the $display_product variable.
					$used_asins=array();
					# Create new array to hold all display product.
					$display_product=array();

					# Loop throught the products.
					foreach($parsed_xml->Items->Item as $key=>$product)
					{
						# Check if this is an item.
						if($key=='Item')
						{
							# Create a variable to hold whether or not a "more" link should be displayed. Default is FALSE.
							$more=FALSE;
							# Set all relevant Data members.
							$this->setAmazonDataMembers($product, $image_size);
							# Create a variable for the ASIN.
							$asin=$this->getASIN();
							# Check if this ASIN has already been used.
							if(!in_array($asin, $used_asins))
							{
								# Add this ASIN to the used ASIN's.
								$used_asins[]=$asin;
								# Make the display product array multi-dimensional.
								$display_product[$asin]=array('author'=>NULL, 'buy'=>NULL, 'detailed_page_url'=>NULL, 'editorial_review'=>NULL, 'image'=>NULL, 'price'=>NULL, 'publisher'=>NULL, 'title'=>NULL, 'edit'=>NULL, 'delete'=>NULL);
								# Set the author to a variable.
								$author=$this->getAuthor();
								# Set the currency to a variable.
								$currency=$this->getCurrency();
								# Set the product's detailed page URL to a variable.
								$detailed_page_url=$this->getDetailedPageURL();
								# Create variable for the product's editorial review.
								$editorial_review=$this->getEditorialReview();
								# Create variable for product image's height.
								$image_height=$this->getImageHeight();
								# Create variable for product image's width.
								$image_width=$this->getImageWidth();
								# Create variable for product image's URL.
								$image_url=$this->getImageURL();
								# Create variable for product original image's URL.
								$image_original_url=$this->getImageOriginalURL();
								# Create variable for the product's price.
								$price=$this->getPrice();
								# Create variable for the product's publisher.
								$publisher=$this->getPublisher();
								# Create variable for the product's title.
								$title=$this->getTitle();

								# Check if a maximum number of characters to be displayed has been passed.
								if($max_char!==NULL)
								{
									# Check if there is an editorial review of the product to display.
									if(!empty($editorial_review))
									{
										# Strip tags from the editorial review and see if it contains more characters than allotted in the maximum characters variable.
										if(strlen(strip_tags($editorial_review)) > $max_char)
										{
											# Use limitStringLength from the Document class to truncate the editorial review.
											$editorial_review=WebUtility::truncate($editorial_review, $max_char, '...%1s', TRUE);
											# Add a "more" link to the editorial review.
											$editorial_review=sprintf($editorial_review, '<a class="more" href="'.$detailed_page_url.'" title="more on: '.$title.'" target="_blank">'.$this->getMore().'</a>');
											# Set the $more value to TRUE.
											$more=TRUE;
										}
									}
								}

								# Set the author content to the array.
								$display_product[$asin]['author']=$this->createAuthorMarkup($author, array(
									'author_class'=>$author_class,
									'author_label'=>$author_label,
									'author_label_class'=>$author_label_class,
									'author_link'=>$author_link,
									'title_attribute'=>$author_link_title
								));

								# Get the FormGenerator class.
								require_once Utility::locateFile(MODULES.'Form'.DS.'FormGenerator.php');
								# Instantiate a new FormGenerator object and creat the add to cart form/button.
								$fg=new FormGenerator($buy_amazon_button_name, 'http://www.amazon.com/gp/aws/cart/add.html', 'GET', '_blank', FALSE, $buy_amazon_button_class);
								$fg->addElement('hidden', array('name'=>'AWSAccessKeyId', 'value'=>$this->getAccessKey()));
								$fg->addElement('hidden', array('name'=>'AssociateTag', 'value'=>$this->getAssTag()));
								$fg->addElement('hidden', array('name'=>'ASIN.1','value'=>$asin));
								$fg->addElement('hidden', array('name'=>'Quantity.1', 'value'=>'1'));
								if(!empty($buy_amazon_label))
								{
									$fg->addFormPart('<span class="'.$buy_amazon_label_class.'">'.$buy_amazon_label.'</span>');
								}
								$fg->addElement($buy_amazon_submit_type, array('name'=>$buy_amazon_submit_name, 'value'=>$buy_amazon_submit_value), '', $buy_amazon_submit_image, $buy_amazon_submit_class);
								# Create a variable to hold the product image display XHTML.
								$buy_content=$fg->display();
								# Set the image content to the array.
								$display_product[$asin]['buy']=$buy_content;

								# Set the product detailed page URL to the array.
								$display_product[$asin]['detailed_page_url']=$detailed_page_url;

								# Check if an editorial review of the product is available to display.
								if(!empty($editorial_review))
								{
									# Set the review display XHTML to a variable.
									$review_content='<span class="content desc">';
									# Check if labels should be displayed.
									if($labels===TRUE)
									{
										# Add the label to the review display XHTML.
										$review_content.='<span class="label">Editorial Review:</span>';
									}
									$review_content.=$editorial_review;
									$review_content.='</span>';
									# Set the review content to the array.
									$display_product[$asin]['editorial_review']=$review_content;
								}

								# Set the image content to the array.
								$display_product[$asin]['image']=$this->createImageMarkup($image_url, array(
									'image_link'=>$image_link,
									'image_original_url'=>$image_original_url,
									'product_title'=>$title
								));

								# Set the price content to the array.
								$display_product[$asin]['price']=$this->createPriceMarkup($price, array(
									'currency'=>$currency,
									'detailed_page_url'=>$detailed_page_url,
									'price_class'=>$price_class,
									'price_label'=>$price_label,
									'price_label_class'=>$price_label_class,
									'price_link'=>$price_link,
									'product_title'=>$title,
									'title_attribute'=>$price_link_title
								), FALSE);

								# Set the publisher content to the array.
								$display_product[$asin]['publisher']=$this->createPublisherMarkup($publisher, array(
									'publisher_class'=>$publisher_class,
									'publisher_label'=>$publisher_label,
									'publisher_label_class'=>$publisher_label_class,
									'publisher_link'=>NULL,
									'title_attribute'=>$publisher_link_title
								));

								# Set the title content to the array.
								$display_product[$asin]['title']=$this->createTitleMarkup($title, array(
									'detailed_page_url'=>$detailed_page_url,
									'title_class'=>$title_class,
									'title_label'=>$title_label,
									'title_label_class'=>$title_label_class,
									'title_link'=>$title_link,
									'title_attribute'=>$title_link_title
								));

								# Check if there should be an edit button displayed.
								if($edit===TRUE)
								{
									# Set the edit button to a variable.
									$edit_content='<a href="'.ADMIN_URL.'product/?amazon='.$asin.'" class="'.$edit_class.'" title="'.$edit_link_title.'">'.$edit_value.'</a>';
									# Set the edit content to the array.
									$display_product[$asin]['edit']=$edit_content;
								}

								# Check f there should be a delete button displayed.
								if($delete===TRUE)
								{
									# Set the delete button to a variable.
									$delete_content='<a href="'.ADMIN_URL.'product/?amazon='.$asin.'&delete" class="'.$delete_class.'" title="'.$delete_link_title.'">'.$delete_value.'</a>';
									# Set the delete content to the array.
									$display_product[$asin]['delete']=$delete_content;
								}
							}
						}
					}
					return $display_product;
				}
				return FALSE;
			}
			catch(Exception $e)
			{
				throw $e;
			}
		} #==== End -- displayAmazonProduct

		/*
		 * getAmazonProduct
		 *
		 * Generates a link to the Amazon shopping cart.
		 *
		 * @param	$page
		 * @param	$identifier
		 * @param	array $asins		An array of product ASIN's.
		 * @access	public
		 */
		public function getAmazonProduct($page, $identifier, $asins)
		{
			# Get the response from Amazon and save it in the cache.
			$response=$this->createCache($page, $identifier, $asins);
			# Parse the returned xml and set it to the data member.
			$this->setParsedXML(simplexml_load_string($response));
		} #==== End -- getAmazonProduct

		/*
		 * makeCartLink
		 *
		 * Generates a link to the Amazon shopping cart.
		 *
		 * @access	public
		 */
		public function makeCartLink()
		{
			$api=new API();

			$CartId=$_GET['CartId'];

			if($CartId=='')
			{
				return '<a href="/cart/">(0) Items in Cart</a>';
			}
			else
			{
				$response=$api->fileGetContentsCurl($this->createCartRequest());
				$parsed_xml=simplexml_load_string($response);
			}
		} #==== End -- makeCartLink

		/*** End public methods ***/



		/*** protected methods ***/

		/*
		 * createImageMarkup
		 *
		 * Creates and returns HTML markup for the "image" of a product.
		 *
		 * @param		$image					The image URL of the product. A String.
		 * @param		$image_options	The markup options. An array.
		 * @access	protected
		 */
		protected function createImageMarkup($image, $image_options)
		{
			# Check if the image is available to display.
			if(!empty($image))
			{
				# Create a variable to hold the product image display HTML.
				$image_content='<img src="'.$image.'" class="image" alt="'.$image_options['product_title'].'"/>';

				# Check if there should be a link for the image.
				if(!empty($image_options['image_link']))
				{
					# Check if the image link is the fwPopUp handle.
					if($image_options['image_link']==FW_POPUP_HANDLE)
					{
						$image_options['image_link']='<a href="'.$image_options['image_original_url'].'" rel="'.FW_POPUP_HANDLE.'" title="'.$image_options['product_title'].'" class="image-link" target="_blank">%{insert_content}</a>';
					}
					else
					{
						$image_options['image_link']=$image_options['image_link'].'%{insert_content}</a>';
					}
				}
				else
				{
					# Explicitly set the image link to an empty variable.
					$image_options['image_link']='%{insert_content}';
				}

				# Put the image inside its container.
				$image_content=str_replace('%{insert_content}', $image_content, $image_options['image_link']);

				# Return the markup.
				return $image_content;
			}
			return NULL;
		} #==== End -- createImageMarkup

/*** THIS MAY NEED SOME FIXING ***/
		protected function createCartRequest($CartId)
		{
			$url='';
			$url.='http://ecs.amazonaws.com/onca/xml?Service=AWSECommerceService&AWSAccessKeyId='.$this->getAccessKey().'&AssociateTag='.$this->getAssTag().'&Operation=CartGet&CartId='.$CartId.'&';

			$url=$this->sendRequest($this->getSecretKey(), $url);
		} #==== End -- createCartRequest
/*** THIS MAY NEED SOME FIXING ***/

/*** THIS NEEDS IMPLEMENTATION ***/
		protected function CartAdd()
		{

		} #==== End -- CartAdd
/*** THIS NEEDS IMPLEMENTATION ***/

		/*
		 * createRequest
		 *
		 * Creates the request link for Amazon API
		 *
		 * @param		array			$asins	(An array of product ASIN's.)
		 * @access	protected
		 */
		protected function createRequest($asins)
		{
			$config['ResponseGroup']="ItemAttributes,OfferSummary,Images,EditorialReview";

			$url='';
			$url.=self::REQUEST_URL.'Service=AWSECommerceService&AWSAccessKeyId='.$this->getAccessKey().'&AssociateTag='.$this->getAssTag().'&Operation=ItemLookup&IdType=ASIN&ItemId=';

			foreach($asins as $asin)
			{
				$url.=$asin.',';
			}

			$url.='&ResponseGroup='.$config['ResponseGroup'].'&MerchantId=All';
			$url=$this->sendRequest($this->getSecretKey(), $url);
			return $url;
		} #==== End -- createRequest

		/*
		 * fixAmazonImageProtocal
		 *
		 * The image URL's from Amazonare returned as insecure URLs. This checks
		 * the protocal and fixes the URL of the image to ensure that secure
		 * requests are made over SSL and regular requests are made when the protocal
		 * is http://.
		 *
		 * @param		String			$image_url	(The URL to check and possibly change)
		 * @access	protected
		 */
		protected function fixAmazonImageProtocal($image_url)
		{
			# Check if the PROTOCAL is https.
			if(PROTOCAL=='https://')
			{
				$image_url=str_replace(self::IMAGE_URL_PREFIX, self::IMAGE_URL_PREFIX_SSL, $image_url);
			}
			return $image_url;
		} #==== End -- fixAmazonImageProtocal

		/*
		 * sendRequest
		 *
		 * Sends a request to the Amazon API
		 *
		 * @param	$secretKey			Secret encryption key
		 * @param	$request			Requested URL
		 * @param	$accessKeyID		Public encryption key
		 * @param	$version			Version of Amazon API
		 * @access	protected
		 */
		protected function sendRequest($secretKey, $request, $accessKeyID="", $version="2010-06-01")
		{
			# Get host and url
			$url = parse_url($request);

			# Get Parameters of request
			$request = $url['query'];
			$parameters = array();
			parse_str($request, $parameters);
			$parameters["Timestamp"] = gmdate("Y-m-d\TH:i:s\Z");
			$parameters["Version"] = $version;
			if($accessKeyID != '') $parameters["AWSAccessKeyId"] = $accessKeyID;

			# Sort paramters
			ksort($parameters);

			# re-build the request
			$request = array();
			foreach ($parameters as $parameter=>$value)
			{
				$parameter = str_replace("_",".",str_replace("%7E", "~", rawurlencode($parameter)));
				$value = str_replace("%7E", "~", rawurlencode($value));
				$request[] = $parameter . "=" . $value;
			}
			$request = implode("&", $request);
			$signatureString = "GET" . chr(10) . $url['host'] . chr(10) . $url['path'] . chr(10) . $request;
			$signature = urlencode(base64_encode(self::hmac($secretKey, $signatureString)));
			$request = "http://".$url['host'].$url['path']."?".$request."&Signature=".$signature;
			return $request;
		} #==== End -- sendRequest

		/*
		 * createCache
		 *
		 * Makes the cache directory and file, creates and sends the request to Amazon, sets the response to the cache, and returns the response.
		 *
		 * @param	array $asins		An array of product ASIN's.
		 * @param	string $page		Get's the $page parameter in the URL.
		 * @param	string $unique
		 * @access	protected
		 */
		protected function createCache($page, $unique, $asins)
		{
			$cache_time=3600;
			$ext='amazon';
			$microtime['cache read'][0]=WebUtility::getMicrotime();	# time before reading cache
			$page=((isset($_GET[$page])) ? $_GET[$page] : '');
			$unique_name=str_replace('\\', '.', trim($page, '/')).$unique;

			# Get the Cache class.
			require_once Utility::locateFile(MODULES.'Cache'.DS.'Cache.php');
			# Instantiate a new Cache object.
			$cache=new Cache(NULL, $unique_name, $ext, $cache_time, CACHE);

			$response=$cache->retrieveCacheContents($unique_name.'.'.$ext);
			if($response!==FALSE)
			{
				# The time after reading cache.
				$microtime['cache read'][1]=WebUtility::getMicrotime();
			}
			else
			{
				$microtime['cache read'][1]=WebUtility::getMicrotime();

				# Get the API Class.
				require_once Utility::locateFile(MODULES.'API'.DS.'API.php');
				# Instantiate a new API object.
				$api=new API();
				if($response=$api->fileGetContentsCurl($this->createRequest($asins)))
				{
					$cache->setFileData($response);
					$microtime['cache write'][0]=WebUtility::getMicrotime();
					try
					{
						$cache->createCache();
					}
					catch(Exception $e)
					{
						throw $e;
					}
					$microtime['cache write'][1]=WebUtility::getMicrotime();
				}
			}
			return $response;
		} #==== End -- createCache

		/*
		 * charPad
		 *
		 * Do the SHA-256 Padding routine (make input a multiple of 512 bits)
		 *
		 * @param	string $str
		 * @access	protected
		 */
		protected function charPad($str)
		{
			$tmpStr = $str;

			$l = strlen($tmpStr)*8;		# of bits from input string

			$tmpStr .= "\x80";		# append the "1" bit followed by 7 0's

			$k = (512 - (($l + 8 + 64) % 512)) / 8;	# of 0 bytes to append
			$k += 4;		# PHP String's will never exceed (2^31)-1, so 1st 32bits of
			# the 64-bit value representing $l can be all 0's

			for($x = 0; $x < $k; $x++)
			{
				$tmpStr .= "\0";
			}

			# append the last 32-bits representing the # of bits from input string ($l)
			$tmpStr .= chr((($l>>24) & 0xFF));
			$tmpStr .= chr((($l>>16) & 0xFF));
			$tmpStr .= chr((($l>>8) & 0xFF));
			$tmpStr .= chr(($l & 0xFF));

			return $tmpStr;
		} #==== End -- charPad

		/*** bitwise and custom methods as defined in FIPS180-2 Standard ***/

		/*
		 * addMod2N
		 *
		 * Z = (X + Y) mod 2^32
		 *
		 * @param	$x
		 * @param	$y
		 * @param	$n
		 * @access	protected
		 */
		protected function addMod2N($x, $y, $n=4294967296)
		{
			$mask = 0x80000000;

			if($x < 0)
			{
				$x &= 0x7FFFFFFF;
				$x = (float)$x + $mask;
			}

			if($y < 0)
			{
				$y &= 0x7FFFFFFF;
				$y = (float)$y + $mask;
			}

			$r = $x + $y;

			if($r >= $n)
			{
				while($r >= $n)
				{
					$r -= $n;
				}
			}

			return (int)$r;
		} #==== End -- addMod2N

		/*
		 * SHR
		 *
		 * Logical bitwise right shift (PHP default is arithmetic shift)
		 *
		 * @param	$x
		 * @param	$n
		 * @access	protected
		 */
		protected function SHR($x, $n)
		{
			if($n >= 32)			# impose some limits to keep it 32-bit
				return (int)0;

			if($n <= 0)
			{
				return (int)$x;
			}

			$mask = 0x40000000;

			if($x < 0)
			{
				$x &= 0x7FFFFFFF;
				$mask = $mask >> ($n-1);
				return ($x >> $n) | $mask;
			}
			return (int)$x >> (int)$n;
		} #==== End -- SHR

		protected function ROTR($x, $n)
		{
			return (int)($this->SHR($x, $n) | ($x << (32-$n)));
		} #==== End -- ROTR

		protected function Ch($x, $y, $z)
		{
			return ($x & $y) ^ ((~$x) & $z);
		} #==== End -- Ch

		protected function Maj($x, $y, $z)
		{
			return ($x & $y) ^ ($x & $z) ^ ($y & $z);
		} #==== End -- Maj

		protected function Sigma0($x)
		{
			return (int) ($this->ROTR($x, 2)^$this->ROTR($x, 13)^$this->ROTR($x, 22));
		} #==== End -- Sigma0

		protected function Sigma1($x)
		{
			return (int) ($this->ROTR($x, 6)^$this->ROTR($x, 11)^$this->ROTR($x, 25));
		} #==== End -- Sigma1

		protected function sigma_0($x)
		{
			return (int) ($this->ROTR($x, 7)^$this->ROTR($x, 18)^$this->SHR($x, 3));
		} #==== End -- sigma_0

		protected function sigma_1($x)
		{
			return (int) ($this->ROTR($x, 17)^$this->ROTR($x, 19)^$this->SHR($x, 10));
		} #==== End -- sigma_1

		/*** End bitwise and custom methods as defined in FIPS180-2 Standard ***/


		/*** custom methods to provide PHP support ***/

		/*
		 * intSplit
		 *
		 * Split a byte-string into integer array values.
		 *
		 * @param	$input (The byte-string.)
		 * @access	protected
		 */
		protected function intSplit($input)
		{
			$l = strlen($input);

			if($l <= 0)        		# right...
				return (int)0;

			if(($l % 4) != 0)  		# invalid input
				return false;

			for ($i = 0; $i < $l; $i += 4)
			{
				$int_build  = (ord($input[$i]) << 24);
				$int_build += (ord($input[$i+1]) << 16);
				$int_build += (ord($input[$i+2]) << 8);
				$int_build += (ord($input[$i+3]));

				$result[] = $int_build;
			}

			return $result;
		} #==== End -- intSplit

		protected function strSplit($string, $split_length = 1)
		{
			$sign = (($split_length < 0) ? -1 : 1);
			$strlen = strlen($string);
			$split_length = abs($split_length);

			if(($split_length == 0) || ($strlen == 0))
			{
				$result = false;
			}
			elseif($split_length >= $strlen)
			{
				$result[] = $string;
			}
			else
			{
				$length = $split_length;

				for ($i = 0; $i < $strlen; $i++)
				{
					$i = (($sign < 0) ? $i + $length : $i);
					$result[] = substr($string, $sign*$i, $length);
					$i--;
					$i = (($sign < 0) ? $i : $i + $length);

					if(($i + $split_length) > ($strlen))
					{
						$length = $strlen - ($i + 1);
					}
					else
					{
						$length = $split_length;
					}
				}
			}

			return $result;
		} #==== End -- strSplit

		/*
		 * sha256
		 *
		 * Note:
		 * PHP Strings are limitd to (2^31)-1, so it is not worth it to
		 * check for input strings > 2^64 as the FIPS180-2 defines.
		 *
		 * @param	$ig_func
		 * @param	$str
		 * @access	protected
		 */
		protected function sha256($str, $ig_func = false)
		{
			unset($binStr);			# binary representation of input string
			unset($hexStr);			# 256-bit message digest in readable hex format

			# check for php 5.1.2's internal sha256 function, ignore if ig_func is true
			if($ig_func == false)
				if(function_exists("hash"))
					return hash("sha256", $str, false);

			/*
			 * Use PHP Implementation of SHA-256 if no other library is available
			 * - This method is much slower, but adds an additional level of fault tolerance
			 */

			# SHA-256 Constants
			# sequence of sixty-four constant 32-bit words representing the first thirty-two bits
			# of the fractional parts of the cube roots of the first sixtyfour prime numbers.
			$K = array((int)0x428a2f98, (int)0x71374491, (int)0xb5c0fbcf, (int)0xe9b5dba5,
				(int)0x3956c25b, (int)0x59f111f1, (int)0x923f82a4, (int)0xab1c5ed5,
				(int)0xd807aa98, (int)0x12835b01, (int)0x243185be, (int)0x550c7dc3,
				(int)0x72be5d74, (int)0x80deb1fe, (int)0x9bdc06a7, (int)0xc19bf174,
				(int)0xe49b69c1, (int)0xefbe4786, (int)0x0fc19dc6, (int)0x240ca1cc,
				(int)0x2de92c6f, (int)0x4a7484aa, (int)0x5cb0a9dc, (int)0x76f988da,
				(int)0x983e5152, (int)0xa831c66d, (int)0xb00327c8, (int)0xbf597fc7,
				(int)0xc6e00bf3, (int)0xd5a79147, (int)0x06ca6351, (int)0x14292967,
				(int)0x27b70a85, (int)0x2e1b2138, (int)0x4d2c6dfc, (int)0x53380d13,
				(int)0x650a7354, (int)0x766a0abb, (int)0x81c2c92e, (int)0x92722c85,
				(int)0xa2bfe8a1, (int)0xa81a664b, (int)0xc24b8b70, (int)0xc76c51a3,
				(int)0xd192e819, (int)0xd6990624, (int)0xf40e3585, (int)0x106aa070,
				(int)0x19a4c116, (int)0x1e376c08, (int)0x2748774c, (int)0x34b0bcb5,
				(int)0x391c0cb3, (int)0x4ed8aa4a, (int)0x5b9cca4f, (int)0x682e6ff3,
				(int)0x748f82ee, (int)0x78a5636f, (int)0x84c87814, (int)0x8cc70208,
				(int)0x90befffa, (int)0xa4506ceb, (int)0xbef9a3f7, (int)0xc67178f2);

			// Pre-processing: Padding the string
			$binStr=$this->charPad($str);

			// Parsing the Padded Message (Break into N 512-bit blocks)
			$M=$this->strSplit($binStr, 64);

			// Set the initial hash values
			$h[0] = (int)0x6a09e667;
			$h[1] = (int)0xbb67ae85;
			$h[2] = (int)0x3c6ef372;
			$h[3] = (int)0xa54ff53a;
			$h[4] = (int)0x510e527f;
			$h[5] = (int)0x9b05688c;
			$h[6] = (int)0x1f83d9ab;
			$h[7] = (int)0x5be0cd19;

			// loop through message blocks and compute hash. ( For i=1 to N : )
			for ($i = 0; $i < count($M); $i++)
			{
				// Break input block into 16 32-bit words (message schedule prep)
				$MI=$this->intSplit($M[$i]);

				// Initialize working variables
				$_a = (int)$h[0];
				$_b = (int)$h[1];
				$_c = (int)$h[2];
				$_d = (int)$h[3];
				$_e = (int)$h[4];
				$_f = (int)$h[5];
				$_g = (int)$h[6];
				$_h = (int)$h[7];
				unset($_s0);
				unset($_s1);
				unset($_T1);
				unset($_T2);
				$W = array();

				// Compute the hash and update
				for ($t = 0; $t < 16; $t++)
				{
					# Prepare the first 16 message schedule values as we loop
					$W[$t] = $MI[$t];

					# Compute hash
					$_T1 = $this->addMod2N($this->addMod2N($this->addMod2N($this->addMod2N($_h, self::Sigma1($_e)), self::Ch($_e, $_f, $_g)), $K[$t]), $W[$t]);
					$_T2 = $this->addMod2N(self::Sigma0($_a), self::Maj($_a, $_b, $_c));

					# Update working variables
					$_h = $_g; $_g = $_f; $_f = $_e; $_e = $this->addMod2N($_d, $_T1);
					$_d = $_c; $_c = $_b; $_b = $_a; $_a = $this->addMod2N($_T1, $_T2);
				}

				for (; $t < 64; $t++)
				{
					# Continue building the message schedule as we loop
					$_s0 = $W[($t+1)&0x0F];
					$_s0 = $sh->sigma_0($_s0);
					$_s1 = $W[($t+14)&0x0F];
					$_s1 = $sh->sigma_1($_s1);

					$W[$t&0xF] = $this->addMod2N($this->addMod2N($this->addMod2N($W[$t&0xF], $_s0), $_s1), $W[($t+9)&0x0F]);

					# Compute hash
					$_T1 = $this->addMod2N($this->addMod2N($this->addMod2N($this->addMod2N($_h, self::Sigma1($_e)), self::Ch($_e, $_f, $_g)), $K[$t]), $W[$t&0xF]);
					$_T2 = $this->addMod2N(self::Sigma0($_a), self::Maj($_a, $_b, $_c));

					# Update working variables
					$_h = $_g; $_g = $_f; $_f = $_e; $_e = $this->addMod2N($_d, $_T1);
					$_d = $_c; $_c = $_b; $_b = $_a; $_a = $this->addMod2N($_T1, $_T2);
				}

				$h[0] = $this->addMod2N($h[0], $_a);
				$h[1] = $this->addMod2N($h[1], $_b);
				$h[2] = $this->addMod2N($h[2], $_c);
				$h[3] = $this->addMod2N($h[3], $_d);
				$h[4] = $this->addMod2N($h[4], $_e);
				$h[5] = $this->addMod2N($h[5], $_f);
				$h[6] = $this->addMod2N($h[6], $_g);
				$h[7] = $this->addMod2N($h[7], $_h);
			}

			# Convert the 32-bit words into human readable hexadecimal format.
			$hexStr = sprintf("%08x%08x%08x%08x%08x%08x%08x%08x", $h[0], $h[1], $h[2], $h[3], $h[4], $h[5], $h[6], $h[7]);
			return $hexStr;
		} #==== End -- sha256

		protected function hmac($key, $data, $hashfunc='sha256')
		{
			$blocksize=64;

			if(strlen($key) > $blocksize) $key=pack('H*', $hashfunc($key));
			$key=str_pad($key, $blocksize, chr(0x00));
			$ipad=str_repeat(chr(0x36), $blocksize);
			$opad=str_repeat(chr(0x5c), $blocksize);
			$hmac = pack('H*', self::$hashfunc(($key^$opad) . pack('H*', self::$hashfunc(($key^$ipad) . $data))));
			return $hmac;
		} #==== End -- hmac

		/*** End custom methods to provide PHP support ***/

		/*
		 * setDataMembers
		 *
		 * Sets all the data returned from the parsed xml to the appropriate Data members.
		 *
		 * @param	$product
		 * @param	$image_size
		 * @access	public
		 */
		public function setAmazonDataMembers($product, $image_size)
		{
			try
			{
				# Reset all the data members.
				$this->setASIN(NULL);
				$this->setAuthor(NULL);
				$this->setDetailedPageURL(NULL);
				$this->setEditorialReview(NULL);
				$this->setImageHeight(NULL);
				$this->setImageWidth(NULL);
				$this->setImageURL(NULL);
				$this->setImageOriginalURL(NULL);
				$this->setPrice(NULL);
				$this->setPublisher(NULL);
				$this->setTitle(NULL);
				# Create empty varaible to hold the product data.
				$author=NULL;
				$detail_page_url=NULL;
				$editorial_review=NULL;
				$image_height=NULL;
				$image_width=NULL;
				$image_url=IMAGES.'no.image.available.gif';
				$image_original_url=IMAGES.'original/no.image.available.gif';
				$price=NULL;
				$currency=NULL;
				$publisher=NULL;
				# Check if the product data is set.
				if(isset($product->DetailPageURL))
				{
					# Set the DetailPageURL to a variable.
					$detail_page_url=$product->DetailPageURL;
				}
				# Check if the product data is set.
				if(isset($product->ItemAttributes->Author))
				{
					# Set the author to a variable.
					$author=$product->ItemAttributes->Author;
				}
				if(isset($product->EditorialReviews->EditorialReview->Content))
				{
					# Set the editorial review to a variable.
					$editorial_review=$product->EditorialReviews->EditorialReview->Content;
				}
				if(isset($product->$image_size->URL))
				{
					# Set the product image's height to a variable.
					$image_height=$product->$image_size->Height;
					# Set the product image's width to a variable.
					$image_width=$product->$image_size->Width;
					# Set the product image's URL to a variable.
					$image_url=$this->fixAmazonImageProtocal($product->$image_size->URL);
					# Set the product original image's URL to a variable.
					$image_original_url=$this->fixAmazonImageProtocal($product->LargeImage->URL);
				}
				if(isset($product->OfferSummary->LowestNewPrice->FormattedPrice))
				{
					# Set the price to a variable.
					$price=$product->OfferSummary->LowestNewPrice->FormattedPrice;
					if(isset($product->OfferSummary->LowestNewPrice->CurrencyCode))
					{
						# Set the currency to a variable.
						$currency=$product->OfferSummary->LowestNewPrice->CurrencyCode;
					}
				}
				else
				{
					if(isset($product->OfferSummary->LowestUsedPrice->FormattedPrice))
					{
						# Set the price to a variable.
						$price=$product->OfferSummary->LowestUsedPrice->FormattedPrice;
						if(isset($product->OfferSummary->LowestUsedPrice->CurrencyCode))
						{
							# Set the currency to a variable.
							$currency=$product->OfferSummary->LowestUsedPrice->CurrencyCode;
						}
					}
				}
				if(isset($product->ItemAttributes->Publisher))
				{
					# Set the publisher to a variable.
					$publisher=$product->ItemAttributes->Publisher;
				}
				# Set Amazon product ASIN (Amazon Product #) to the data member.
				$this->setASIN($product->ASIN);
				# Set Amazon product's author to the data member.
				$this->setAuthor($author);
				# Set Amazon product's author to the data member.
				$this->setCurrency($currency);
				# Set the URL for the Amazon product's detail page to the data member.
				$this->setDetailedPageURL($detail_page_url);
				# Set the editorial review of the Amazon product to the data member.
				$this->setEditorialReview($editorial_review);
				# Set Amazon product image's height for the size of image passed to the data member.
				$this->setImageHeight($image_height);
				# Set Amazon product image's width for the size of image passed to the data member.
				$this->setImageWidth($image_width);
				# Set Amazon product image URL for the size of image passed to the data member.
				$this->setImageURL($image_url);
				# Set Amazon product original image URL to the data member.
				$this->setImageOriginalURL($image_original_url);
				# Set the lowest new Amazon price of the Amazon product to the data member.
				$this->setPrice($price);
				# Set Amazon product's publisher to the data member.
				$this->setPublisher($publisher);
				if(isset($product->ItemAttributes->Title))
				{
					# Set passed title array to a variable.
					$title=$product->ItemAttributes->Title;
					# Get Amazon product's title from the passed data array.
					$title=$title[0];
					# Set Amazon product's title to the data member.
					$this->setTitle($title);
				}
			}
			catch(Exception $e)
			{
				throw $e;
			}
		} #==== End -- setDataMembers

		/*** End protected methods ***/

	} # end Amazon class

} # end if defined