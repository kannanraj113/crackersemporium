<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/contrib/belgrade/templates/block/block--system-powered-by-block.html.twig */
class __TwigTemplate_4c727138fef7ccdc4eaec2fbc01bbe46f3fb9072313330cd5d62dd280dc5f177 extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'content' => [$this, 'block_content'],
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doGetParent(array $context)
    {
        // line 29
        return "@belgrade/block/block.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 31
        twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => [0 => "col-sm-6", 1 => "col-md-3", 2 => "mb-3"]], "method", false, false, true, 31);
        // line 29
        $this->parent = $this->loadTemplate("@belgrade/block/block.html.twig", "themes/contrib/belgrade/templates/block/block--system-powered-by-block.html.twig", 29);
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 33
    public function block_content($context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 34
        echo "  <a class=\"footer--logo\" href=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar($this->extensions['Drupal\Core\Template\TwigExtension']->getPath("<front>"));
        echo "\" title=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(t("Home"));
        echo "\" rel=\"home\">
    ";
        // line 35
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->renderVar(twig_source($this->env, ($this->sandbox->ensureToStringAllowed(($context["directory"] ?? null), 35, $this->source) . "/dist/images/logo-graphic.svg"), true));
        echo "
  </a>
  <div>
    ";
        // line 38
        $this->displayParentBlock("content", $context, $blocks);
        echo "
  </div>
";
    }

    public function getTemplateName()
    {
        return "themes/contrib/belgrade/templates/block/block--system-powered-by-block.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  68 => 38,  62 => 35,  55 => 34,  51 => 33,  46 => 29,  44 => 31,  37 => 29,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/contrib/belgrade/templates/block/block--system-powered-by-block.html.twig", "/var/www/html/themes/contrib/belgrade/templates/block/block--system-powered-by-block.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("do" => 31);
        static $filters = array("t" => 34);
        static $functions = array("path" => 34, "source" => 35);

        try {
            $this->sandbox->checkSecurity(
                ['do'],
                ['t'],
                ['path', 'source']
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
