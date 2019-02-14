<?php

//require_once 'class.styleconstants.php';

include_once 'phpodt.php';

/**
 * The class responsible for creating the xml documents needed
 * to generate an ODT document
 *
 * @author Issam RACHDI
 */
class ODT {
	const GENERATOR = 'PHP-ODT 0.3';

	/**
	 * The name of the odt file
	 */
//	private $fileName;
	private $manifest;
	private $styles;
	private $documentContent;
	private $officeBody;
	private $officeText;
	private $metadata;
	private $officeMeta;
//	private $officeStyles;
//	private $officeAutomaticStyles;
//	private $permissions;

	private static $instance;

	/**
	 * @param $fileName The name of the odt file
	 * @param $perm The permissions of the file (optional)
	 */
	private function __construct() {
		$this->initContent();
	}

	static function getInstance() {
		if (self::$instance == null) {
			self::$instance = new ODT();
		}
		return self::$instance;
	}

	/**
	 * Creates the manifest document, wich describe all the files contained in
	 * the odt document
	 */
	function createManifest() {
		$manifestDoc = new DOMDocument('1.0', 'UTF-8');
		$root = $manifestDoc->createElement('manifest:manifest');
		$root->setAttribute('xmlns:manifest', 'urn:oasis:names:tc:opendocument:xmlns:manifest:1.0');
		$root->setAttribute('office:version', "1.1");
		$manifestDoc->appendChild($root);

		$fileEntryRoot = $manifestDoc->createElement('manifest:file-entry');
		$fileEntryRoot->setAttribute('manifest:media-type', 'application/vnd.oasis.opendocument.text');
		$fileEntryRoot->setAttribute('manifest:full-path', '/');
		$root->appendChild($fileEntryRoot);

		$fileEntryContent = $manifestDoc->createElement('manifest:file-entry');
		$fileEntryContent->setAttribute('manifest:media-type', 'text/xml');
		$fileEntryContent->setAttribute('manifest:full-path', 'content.xml');
		$root->appendChild($fileEntryContent);

		$fileEntryStyles = $manifestDoc->createElement('manifest:file-entry');
		$fileEntryStyles->setAttribute('manifest:media-type', 'text/xml');
		$fileEntryStyles->setAttribute('manifest:full-path', 'styles.xml');
		$root->appendChild($fileEntryStyles);

		$fileEntrySettings = $manifestDoc->createElement('manifest:file-entry');
		$fileEntrySettings->setAttribute('manifest:media-type', 'text/xml');
		$fileEntrySettings->setAttribute('manifest:full-path', 'settings.xml');
		$root->appendChild($fileEntrySettings);

		$fileEntryMimetype = $manifestDoc->createElement('manifest:file-entry');
		$fileEntryMimetype->setAttribute('manifest:media-type', 'text/plain');
		$fileEntryMimetype->setAttribute('manifest:full-path', 'mimetype');
		$root->appendChild($fileEntryMimetype);

		$fileEntryMeta = $manifestDoc->createElement('manifest:file-entry');
		$fileEntryMeta->setAttribute('manifest:media-type', 'text/xml');
		$fileEntryMeta->setAttribute('manifest:full-path', 'meta.xml');
		$root->appendChild($fileEntryMeta);

		$this->manifest = $manifestDoc;
	}

	/**
	 * Creates the styles document, which contains all the styles used in the document
	 */
	function createStyle() {
		$this->styles = new DOMDocument('1.0', 'UTF-8');
		$root = $this->styles->createElement('office:document-styles');
		$root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
		$root->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
		$root->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
		$root->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
		$root->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
		$root->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
		$root->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
		$root->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
		$root->setAttribute('office:version', '1.1');
		$this->styles->appendChild($root);

		$this->declareFontFaces($root);

		$officeStyles = $this->styles->createElement('office:styles');
		$root->appendChild($officeStyles);

		$officeAutomaticStyles = $this->styles->createElement('office:automatic-styles');
		$root->appendChild($officeAutomaticStyles);

		$officeMasterStyles = $this->styles->createElement('office:master-styles');
		$root->appendChild($officeMasterStyles);
	}

	/**
	 * Creates the metadata document, containing the general informations about the document,
	 *
	 */
	function createMetadata() {
		$this->metadata = new DOMDocument('1.0', 'UTF-8');
		$root = $this->metadata->createElement('office:document-meta');
		$root->setAttribute('xmlns:meta', 'urn:oasis:names:tc:opendocument:xmlns:meta:1.0');
		$root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
		$root->setAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');
		$root->setAttribute('office:version', '1.1');
		$this->metadata->appendChild($root);

		$generator = $this->metadata->createElement('meta:generator', self::GENERATOR);
		$creationDate = $this->metadata->createElement('meta:creation-date', date('Y-m-d\TH:i:s'));
		$this->officeMeta = $this->metadata->createElement('office:meta');
		$this->officeMeta->appendChild($generator);
		$this->officeMeta->appendChild($creationDate);
		$root->appendChild($this->officeMeta);
	}

	/**
	 * Declare the fonts that can be used in the document
	 *
	 * @param DOMElement $rootStyles The root element of the styles document
	 */
	function declareFontFaces($rootStyles) {
		$fontFaceDecl = $this->styles->createElement('office:font-face-decls');
		$rootStyles->appendChild($fontFaceDecl);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Arial');
		$ff->setAttribute('svg:font-family', 'Arial');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Eras Bk BT');
		$ff->setAttribute('svg:font-family', 'Eras Bk BT');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Eras Md BT');
		$ff->setAttribute('svg:font-family', 'Eras Md BT');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'NewsGotT');
		$ff->setAttribute('svg:font-family', 'NewsGotT');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'NewsGotTDem');
		$ff->setAttribute('svg:font-family', 'NewsGotTDem');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Courier');
		$ff->setAttribute('svg:font-family', 'Courier');
		$ff->setAttribute('style:font-family-generic', 'modern');
		$ff->setAttribute('style:font-pitch', 'fixed');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'DejaVu Serif');
		$ff->setAttribute('svg:font-family', '&apos;DejaVu Serif&apos;');
		$ff->setAttribute('style:font-family-generic', 'roman');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Times New Roman');
		$ff->setAttribute('svg:font-family', '&apos;Times New Roman&apos;');
		$ff->setAttribute('style:font-family-generic', 'roman');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'DejaVu Sans');
		$ff->setAttribute('svg:font-family', '&apos;DejaVu Sans&apos;');
		$ff->setAttribute('style:font-family-generic', 'swiss');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);

		$ff = $this->styles->createElement('style:font-face');
		$ff->setAttribute('style:name', 'Verdana');
		$ff->setAttribute('svg:font-family', 'Verdana');
		$ff->setAttribute('style:font-family-generic', 'swiss');
		$ff->setAttribute('style:font-pitch', 'variable');
		$fontFaceDecl->appendChild($ff);
	}

	/**
	 * Creates the needed documents and does the needed initialization
	 * @return DOMDocument An empty odt document
	 */
	function initContent() {
		$this->createManifest();
		$this->createStyle();
		$this->createMetadata();

		$this->documentContent = new DOMDocument('1.0', 'UTF-8');
		$this->documentContent->substituteEntities = true;
		$root = $this->documentContent->createElement('office:document-content');
		$root->setAttribute('xmlns:office', 'urn:oasis:names:tc:opendocument:xmlns:office:1.0');
		$root->setAttribute('xmlns:text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
		$root->setAttribute('xmlns:draw', 'urn:oasis:names:tc:opendocument:xmlns:drawing:1.0');
		$root->setAttribute('xmlns:svg', 'urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0');
		$root->setAttribute('xmlns:table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
		$root->setAttribute('xmlns:style', 'urn:oasis:names:tc:opendocument:xmlns:style:1.0');
		$root->setAttribute('xmlns:fo', 'urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0');
		$root->setAttribute('xmlns:xlink', 'http://www.w3.org/1999/xlink');
		$root->setAttribute('office:version', '1.1');
		$this->documentContent->appendChild($root);

		$officeAutomaticStyles = $this->documentContent->createElement('office:automatic-styles');
		$root->appendChild($officeAutomaticStyles);


		$this->officeBody = $this->documentContent->createElement('office:body');
		$root->appendChild($this->officeBody);

		$this->officeText = $this->documentContent->createElement('office:text');
		$this->officeBody->appendChild($this->officeText);

//		return $this->documentContent;
	}

	/**
	 * Sets the title of the document
	 *
	 * @param string $title
	 */
	function setTitle($title) {
		$element = $this->metadata->createElement('dc:title', $title);
		$this->officeMeta->appendChild($element);
	}

	/**
	 * Sets a description for the document
	 *
	 * @param string $description
	 */
	function setDescription($description) {
		$element = $this->metadata->createElement('dc:description', $description);
		$this->officeMeta->appendChild($element);
	}

	/**
	 * Sets the subject of the document
	 *
	 * @param string $subject
	 */
	function setSubject($subject) {
		$element = $this->metadata->createElement('dc:subject', $subject);
		$this->officeMeta->appendChild($element);
	}

	/**
	 * Sets the keywords related to the document
	 *
	 * @param array $keywords
	 */
	function setKeywords($keywords) {
		if (!is_array($keywords)) {
			throw new ODTException('Keywords must be an array.');
		}
		foreach ($keywords as $keyword) {
			$element = $this->metadata->createElement('meta:keyword', $keyword);
			$this->officeMeta->appendChild($element);
		}
	}

	/**
	 * Specifies the name of the person who created the document initially
	 *
	 * @param string $creator
	 */
	function setCreator($creator) {
		$element = $this->metadata->createElement('meta:initial-creator', $creator);
		$this->officeMeta->appendChild($element);
	}

	/**
	 *
	 * @return DOMDocument The document containing all the styles
	 */
	function getStyleDocument() {
		return $this->styles;
	}

	public function getDocumentContent() {
		return $this->documentContent;
	}

	/**
	 * Write the document to the hard disk
	 */
	function output($fileName, $perm = 0777) {
		$document = new ZipArchive();
		$document->open($fileName, ZIPARCHIVE::CREATE|ZIPARCHIVE::OVERWRITE);

		$document->addFromString('META-INF/manifest.xml', $this->manifest->saveXML());
		$document->addFromString('styles.xml', $this->styles->saveXML());
		$document->addFromString('meta.xml', $this->metadata->saveXML());
		$document->addFromString('content.xml', html_entity_decode($this->documentContent->saveXML()));
		$document->addFromString('settings.xml', '<?xml version="1.0" encoding="UTF-8"?><office:document-settings office:version="1.1" xmlns:anim="urn:oasis:names:tc:opendocument:xmlns:animation:1.0" xmlns:chart="urn:oasis:names:tc:opendocument:xmlns:chart:1.0" xmlns:config="urn:oasis:names:tc:opendocument:xmlns:config:1.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dr3d="urn:oasis:names:tc:opendocument:xmlns:dr3d:1.0" xmlns:draw="urn:oasis:names:tc:opendocument:xmlns:drawing:1.0" xmlns:fo="urn:oasis:names:tc:opendocument:xmlns:xsl-fo-compatible:1.0" xmlns:form="urn:oasis:names:tc:opendocument:xmlns:form:1.0" xmlns:math="http://www.w3.org/1998/Math/MathML" xmlns:meta="urn:oasis:names:tc:opendocument:xmlns:meta:1.0" xmlns:number="urn:oasis:names:tc:opendocument:xmlns:datastyle:1.0" xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:presentation="urn:oasis:names:tc:opendocument:xmlns:presentation:1.0" xmlns:script="urn:oasis:names:tc:opendocument:xmlns:script:1.0" xmlns:smil="urn:oasis:names:tc:opendocument:xmlns:smil-compatible:1.0" xmlns:style="urn:oasis:names:tc:opendocument:xmlns:style:1.0" xmlns:svg="urn:oasis:names:tc:opendocument:xmlns:svg-compatible:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" xmlns:xforms="http://www.w3.org/2002/xforms" xmlns:xlink="http://www.w3.org/1999/xlink"/>');
		$document->addFromString('mimetype', 'application/vnd.oasis.opendocument.text');
		$document->addFromString('manifest.rdf', '<?xml version="1.0" encoding="utf-8"?><rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"></rdf:RDF>');

		$document->close();

		// Convert hexadecimal to string
		function hexToStr($hex){
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
		}

		// Change ZIP metatype to ODT
		$metatype_hex = '504B03040A0000000000000021005EC6320C2700000027000000080000006D696D65747970656170706C69636174696F6E2F766E642E6F617369732E6F70656E646F63756D656E742E74657874';
		$newDocument = hexToStr($metatype_hex).file_get_contents($fileName);
		file_put_contents($fileName, $newDocument);

		//
		chmod($fileName, $perm);
	}
}
?>
