<? include ('topo.inc') ?>

<h1>Como ajudar?</h1>

<hr>

<p>
Se você nunca lidou com tradução de software e não sabe o
que são "potfiles" dê antes uma olhada no manual do
<a href="http://www.gnu.org/manual/gettext-0.11.2/">gettext</a>.

<p>
Para começar a ajudar você precisa ver quais módulos precisam
ser atualizados. Na <a href="index.php">página principal</a>
há links para as páginas de status de tradução do Gnome para
o pt_BR.

<p>
Depois, você tem de avisar à lista do <a 
href="mailto:gnome-l10n-br@listas.cipsga.org.br">Gnome-l10n-BR</a> que vai
traduzir aquele módulo e esperar por pelo menos um dia pela resposta
do mantenedor do módulo ou coordenador.

<p>
Finalmente, você deve baixar o módulo em que quer trabalhar do
CVS. Muito cuidado nessa etapa. Alguns dos módulos pedem
tradução em <i>branches</i> específicos do CVS. Para descobrir
qual branch precisa de tradução veja o final da página de status
que mostra o status do módulo. Os módulos que devem ter branches
específicos traduzidos estarão listados, por exemplo:

<pre>
eog: gnome-2-0
gconf-editor: gnome-2-0
glib: glib-2-0
</pre>

<p>
No caso acima <i>eog</i> é o módulo, <i>gnome-2-0</i> é o branch
que deve ser traduzido. Aqueles que não estão listados devem ter
o <b>HEAD</b> traduzido.

<p>
Faça o download do script a seguir que pode te ajudar:

<ul>
<li><a href="cvs-gnome">cvs-gnome</a>
</ul>

<p>

Seu uso é muito simples:

<pre>
$ ./cvs-gnome init eog
$ ./cvs-gnome init -r gnome-2-0 eog
$ ./cvs-gnome eog
</pre>

<p>
O primeiro comando baixa o HEAD do eog para o diretório atual.
O segundo baixa o branch <i>gnome-2-0</i>. O terceiro atualiza
seu repositório local, não importando se é HEAD ou um branch.
<b>Importante</b>: o script vai pedir senha. O acesso é feito
com usuário anônimo e não há senha. Basta teclar <i>Enter</i>
quando for solicitada a senha.

<p>
Quando tudo estiver pronto, traduzido, atualizado, envie o
arquivo <i>.po</i> para a lista <a 
href="mailto:gnome-l10n-br@listas.cipsga.org.br">Gnome-l10n-BR</a>
para que uma das pessoas que têm acesso de escrita ao
CVS possam enviar o arquivo. Não se esqueça de compactar
o arquivo com <i>bzip2</i> para diminuir o tráfego
da lista e de indicar informações básicas como qual módulo
você atualizou e qual <i>branch</i>, ou se foi o HEAD.

<p>
Para mais informações veja a <a 
href="http://developer.gnome.org/projects/gtp/resources.html">página
de recursos</a> do Projeto de Tradução do Gnome.

<? include ('fim.inc') ?>
