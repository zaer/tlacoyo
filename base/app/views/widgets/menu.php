	<div class="cbp-spmenu cbp-spmenu-vertical cbp-spmenu-left" id="cbp-spmenu-s1">
		<aside class="sidebar-left">
			<nav class="navbar navbar-inverse">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".collapse" aria-expanded="false">
						<span class="sr-only">Navegación movil</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<h1><a class="navbar-brand" href="<?=APP_HOST_URL?>/index"><span class="fa fa-area-chart"></span> <?=APP_NAME?><span class="dashboard_text">Dashboard</span></a></h1>
				</div>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="sidebar-menu">
						<li class="header">MODULOS [ <?=APP_NAME?> ]</li>
						<li class="treeview">
							<a href="<?=APP_HOST_URL?>/index">
								<i class="fa fa-dashboard"></i> <span>Dashboard</span>
							</a>
						</li>
						<!-- ini menu -->
						<?php
							$db = new DataBase(); $db->connect();
							$menu = new Menu($db);
							$m = $menu->leer_todos($db);
							foreach ($m as $padre)
							{
								$rec = $padre->getId();
								if($padre->getTipo()==0 and $padre->getSub()==0 and $padre->getRuta()=='')
								{
									$x = strtolower(trim($padre->getNombre()));
									$p = $padre->getNombre();
									$id_padre = $padre->getId();
									//verificamos si el usuario tiene permitido visualizar dichos recursos
									?>
									<li class="treeview">
										<a href="#">
											<i class="<?=$padre->getIcono()?>"></i><!-- icono para identificar menu -->
											<span><?=$p?></span><!-- titulo del menu -->
											<i class="fa fa-angle-left pull-right"></i><!-- la flecha indicando que tiene mas -->
										</a>
										<?php
										$sub = $menu->leer_hijos($id_padre);
										if($sub != null)
										{
											echo "<ul class='treeview-menu'>";
											foreach($sub as $hijo)
											{
												$c=$hijo->getController();
												?>
												<li><a href="<?=APP_HOST_URL?>/<?=$c."/".$hijo->getRuta()?>"><i class="fa fa-angle-right"></i> <?=$hijo->getNombre()?></a></li>
												<?php
											}
											echo "</ul>";
										}
										echo "</li>";
								}
								if($padre->getTipo()==0 and $padre->getSub()==0 and $padre->getRuta()!='')
								{
									?>
									<li class="treeview">
										<a href="<?=APP_HOST_URL?>/<?=$padre->getRuta()?>">
											<i class="<?=$padre->getIcono()?>"></i>
											<span><?=$padre->getNombre()?></span>
										</a>
									</li>
									<?php
								}
							}
						?>
						<li class="header">GENERAL</li>
						<li><a href="#"><i class="fa fa-angle-right text-red"></i> <span>Estado de cuenta</span></a></li>
						<li><a href="#"><i class="fa fa-angle-right text-yellow"></i> <span>Avisos</span></a></li>
						<li><a href="#"><i class="fa fa-angle-right text-aqua"></i> <span>Ticket</span></a></li>
					</ul>
				</div>
			</nav>
		</aside>
	</div>