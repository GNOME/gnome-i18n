<? include ('topo.inc') ?>

<h1>Como ajudar?</h1>

<hr>

<p>
Se voc� nunca lidou com tradu��o de software e n�o sabe o
que s�o "potfiles" d� antes uma olhada no manual do
<a href="http://www.gnu.org/manual/gettext-0.11.2/">gettext</a>.

<p>
Para come�ar a ajudar voc� precisa ver quais m�dulos precisam
ser atualizados. Na <a href="index.php">p�gina principal</a>
h� links para as p�ginas de status de tradu��o do Gnome para
o pt_BR.

<p>
Depois, voc� tem de avisar � lista do <a 
href="mailto:gnome-l10n-br@listas.cipsga.org.br">Gnome-l10n-BR</a> que vai
traduzir aquele m�dulo e esperar por pelo menos um dia pela resposta
do mantenedor do m�dulo ou coordenador.

<p>
Finalmente, voc� deve baixar o m�dulo em que quer trabalhar do
CVS. Muito cuidado nessa etapa. Alguns dos m�dulos pedem
tradu��o em <i>branches</i> espec�ficos do CVS. Para descobrir
qual branch precisa de tradu��o veja o final da p�gina de status
que mostra o status do m�dulo. Os m�dulos que devem ter branches
espec�ficos traduzidos estar�o listados, por exemplo:

<pre>
eog: gnome-2-0
gconf-editor: gnome-2-0
glib: glib-2-0
</pre>

<p>
No caso acima <i>eog</i> � o m�dulo, <i>gnome-2-0</i> � o branch
que deve ser traduzido. Aqueles que n�o est�o listados devem ter
o <b>HEAD</b> traduzido.

<p>
Fa�a o download do script a seguir que pode te ajudar:

<ul>
<li><a href="cvs-gnome">cvs-gnome</a>
</ul>

<p>

Seu uso � muito simples:

<pre>
$ ./cvs-gnome init eog
$ ./cvs-gnome init -r gnome-2-0 eog
$ ./cvs-gnome eog
</pre>

<p>
O primeiro comando baixa o HEAD do eog para o diret�rio atual.
O segundo baixa o branch <i>gnome-2-0</i>. O terceiro atualiza
seu reposit�rio local, n�o importando se � HEAD ou um branch.
<b>Importante</b>: o script vai pedir senha. O acesso � feito
com usu�rio an�nimo e n�o h� senha. Basta teclar <i>Enter</i>
quando for solicitada a senha.

<p>
Quando tudo estiver pronto, traduzido, atualizado, envie o
arquivo <i>.po</i> para a lista <a 
href="mailto:gnome-l10n-br@listas.cipsga.org.br">Gnome-l10n-BR</a>
para que uma das pessoas que t�m acesso de escrita ao
CVS possam enviar o arquivo. N�o se esque�a de compactar
o arquivo com <i>bzip2</i> para diminuir o tr�fego
da lista e de indicar informa��es b�sicas como qual m�dulo
voc� atualizou e qual <i>branch</i>, ou se foi o HEAD.

<p>
Para mais informa��es veja a <a 
href="http://developer.gnome.org/projects/gtp/resources.html">p�gina
de recursos</a> do Projeto de Tradu��o do Gnome.

<? include ('fim.inc') ?>
