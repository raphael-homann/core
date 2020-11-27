<?php declare(strict_types=1);

namespace Shopware\Core\Framework\App\Manifest\Xml;

use Shopware\Core\Framework\App\Validation\Error\MissingTranslationError;

class Metadata extends XmlElement
{
    public const TRANSLATABLE_FIELDS = [
        'label',
        'description',
        'privacyPolicyExtensions',
    ];

    /**
     * @var array
     */
    protected $label = [];

    /**
     * @var array
     */
    protected $description = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $author;

    /**
     * @var string
     */
    protected $copyright;

    /**
     * @var string|null
     */
    protected $license;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var string|null
     */
    protected $icon;

    /**
     * @var string|null
     */
    protected $privacy;

    /**
     * @var array
     */
    protected $privacyPolicyExtensions = [];

    private function __construct(array $data)
    {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    public static function fromXml(\DOMElement $element): self
    {
        return new self(self::parse($element));
    }

    public function toArray(string $defaultLocale): array
    {
        $data = parent::toArray($defaultLocale);

        foreach (self::TRANSLATABLE_FIELDS as $TRANSLATABLE_FIELD) {
            $translatableField = self::kebabCaseToCamelCase($TRANSLATABLE_FIELD);

            $data[$translatableField] = $this->ensureTranslationForDefaultLanguageExist(
                $data[$translatableField],
                $defaultLocale
            );
        }

        return $data;
    }

    public function validateTranslations(): ?MissingTranslationError
    {
        // used locales are valid, see Manifest::createFromXmlFile()
        $usedLocales = array_keys(array_merge($this->getDescription(), $this->getPrivacyPolicyExtensions()));

        // label is required in app_translation and must therefore be available in all languages
        $diff = array_diff($usedLocales, array_keys($this->getLabel()));

        if (empty($diff)) {
            return null;
        }

        $missingTranslations['label'] = $diff;

        return new MissingTranslationError(self::class, $missingTranslations);
    }

    public function getLabel(): array
    {
        return $this->label;
    }

    public function getDescription(): array
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCopyright(): string
    {
        return $this->copyright;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getPrivacy(): ?string
    {
        return $this->privacy;
    }

    public function getPrivacyPolicyExtensions(): array
    {
        return $this->privacyPolicyExtensions;
    }

    private static function parse(\DOMElement $element): array
    {
        $values = [];

        foreach ($element->childNodes as $child) {
            if (!$child instanceof \DOMElement) {
                continue;
            }

            // translated
            if (\in_array($child->tagName, self::TRANSLATABLE_FIELDS, true)) {
                $values = self::mapTranslatedTag($child, $values);

                continue;
            }

            $values[$child->tagName] = $child->nodeValue;
        }

        return $values;
    }
}
