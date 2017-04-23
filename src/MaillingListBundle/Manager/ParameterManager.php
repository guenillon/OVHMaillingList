<?php
namespace MaillingListBundle\Manager;

use Symfony\Component\Yaml\Yaml;

class ParameterManager
{
	private $parameterFilename;
	private $rootDir;

	public function __construct($rootDir)
	{
		$this->rootDir= $rootDir;
		$this->parameterFilename = "parameters.yml";
	}
	
	public function updateParameter($parameter, $value)
	{
		try {
			// Le fichier des paramètres
			$parameters = $this->rootDir. "/config/" . $this->parameterFilename;
			// Chargement du fichier en YAML
			$yaml= Yaml::parse(file_get_contents($parameters));
			// Modification de la valeur
			$yaml["parameters"][$parameter] = $value;
			// Conversion en YAML
			$yaml = Yaml::dump($yaml);
			// Enregistrement de la nouvelle configuration
			file_put_contents($parameters, $yaml);
			
		} catch (ParseException $e) {
			printf("Unable to parse the YAML string: %s", $e->getMessage());
		}
	}
}
?>
