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

/* @belgrade/macros.twig */
class __TwigTemplate_cf5ffe5392d632dbaec536cb70ad4b495eb229587f2ab0301fcbe02ef35308ec extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 2
        echo "
";
    }

    // line 3
    public function macro_getIcon($__icon__ = null, $__width__ = null, $__height__ = null, $__classes__ = null, $__path__ = null, $__title__ = null, $__desc__ = null, ...$__varargs__)
    {
        $macros = $this->macros;
        $context = $this->env->mergeGlobals([
            "icon" => $__icon__,
            "width" => $__width__,
            "height" => $__height__,
            "classes" => $__classes__,
            "path" => $__path__,
            "title" => $__title__,
            "desc" => $__desc__,
            "varargs" => $__varargs__,
        ]);

        $blocks = [];

        ob_start(function () { return ''; });
        try {
            // line 4
            echo "
  ";
            // line 5
            $context["title"] = ((($context["title"] ?? null)) ? (($context["title"] ?? null)) : (twig_capitalize_string_filter($this->env, $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 5, $this->source))));
            // line 6
            echo "
  ";
            // line 12
            echo "
  <svg class=\"beo beo-";
            // line 13
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 13, $this->source), "html", null, true);
            echo " ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["classes"] ?? null), 13, $this->source), "html", null, true);
            echo "\"
       width=\"";
            // line 14
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("width", $context)) ? (_twig_default_filter($this->sandbox->ensureToStringAllowed(($context["width"] ?? null), 14, $this->source), 16)) : (16)), "html", null, true);
            echo "\"
       height=\"";
            // line 15
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("height", $context)) ? (_twig_default_filter($this->sandbox->ensureToStringAllowed(($context["height"] ?? null), 15, $this->source), 16)) : (16)), "html", null, true);
            echo "\"
       fill=\"currentColor\"
       aria-hidden=\"true\"
       viewBox=\"0 0 16 16\"
       role=\"img\">
    <title>";
            // line 20
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 20, $this->source), "html", null, true);
            echo "</title>

    ";
            // line 25
            echo "    <use xlink:href=\"";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, ((array_key_exists("path", $context)) ? (_twig_default_filter($this->sandbox->ensureToStringAllowed(($context["path"] ?? null), 25, $this->source), ((("/" . $this->extensions['Drupal\Core\Template\TwigExtension']->getActiveThemePath()) . "/dist/images/belgrade-icons.svg#") . $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 25, $this->source)))) : (((("/" . $this->extensions['Drupal\Core\Template\TwigExtension']->getActiveThemePath()) . "/dist/images/belgrade-icons.svg#") . $this->sandbox->ensureToStringAllowed(($context["icon"] ?? null), 25, $this->source)))), "html", null, true);
            echo "\"/>

    ";
            // line 31
            echo "
    ";
            // line 32
            if (($context["desc"] ?? null)) {
                // line 33
                echo "      <desc>";
                echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["desc"] ?? null), 33, $this->source), "html", null, true);
                echo "</desc>
    ";
            }
            // line 35
            echo "  </svg>

";

            return ('' === $tmp = ob_get_contents()) ? '' : new Markup($tmp, $this->env->getCharset());
        } finally {
            ob_end_clean();
        }
    }

    public function getTemplateName()
    {
        return "@belgrade/macros.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  114 => 35,  108 => 33,  106 => 32,  103 => 31,  97 => 25,  92 => 20,  84 => 15,  80 => 14,  74 => 13,  71 => 12,  68 => 6,  66 => 5,  63 => 4,  44 => 3,  39 => 2,);
    }

    public function getSourceContext()
    {
        return new Source("", "@belgrade/macros.twig", "/var/www/html/themes/contrib/belgrade/templates/macros.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("macro" => 3, "set" => 5, "if" => 32);
        static $filters = array("capitalize" => 5, "escape" => 13, "default" => 14);
        static $functions = array("active_theme_path" => 25);

        try {
            $this->sandbox->checkSecurity(
                ['macro', 'set', 'if'],
                ['capitalize', 'escape', 'default'],
                ['active_theme_path']
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
