---
sidebar_label: From ZF-Commons Rbac v3
sidebar_position: 2
title: Migrating from ZF-Commons RBAC v3 
---

The ZF-Commons Rbac was created for the Zend Framework. When the Zend Framework was migrated to
the Laminas project, the LM-Commons organization was created to provide components formerly provided by ZF-Commons.

When ZfcRbac was moved to LM-Commons, it was split into two repositories:

- [LmcRbacMvc](https://github.com/LM-Commons/LmcRbacMvc) contains the old version 2 of ZfcRbac.
- LmcRbac contains the version 3 of ZfcRbac, which was only released as v3.alpha.1.

To upgrade to LmcRbac v2, it is suggested to do it in two steps: 

1. Upgrade to LmcRbac v1 with the following steps:
   * Uninstall `zf-commons/zfc-rbac:3.0.0-alpha.1`.
   * Install `lm-commons/lmc-rbac:~1.0`
   * Change `zfc-rbac.global.php` to `lmcrbac.global.php` and update the key `zfc_rbac` to `lmc_rbac`.
   * Review your code for usages of the `ZfcRbac/*` namespace to `LmcRbac/*` namespace.
2. Upgrade to LmcRbac v2 using the instructions in this [section](to-v2.md).
