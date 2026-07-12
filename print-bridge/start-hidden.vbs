' Jalankan print-bridge di background tanpa jendela CMD
Set fso = CreateObject("Scripting.FileSystemObject")
Set WshShell = CreateObject("WScript.Shell")
scriptDir = fso.GetParentFolderName(WScript.ScriptFullName)

If Not fso.FolderExists(scriptDir & "\logs") Then
    fso.CreateFolder(scriptDir & "\logs")
End If

If Not fso.FolderExists(scriptDir & "\node_modules") Then
    WshShell.Run "cmd /c cd /d """ & scriptDir & """ && npm install >> logs\setup.log 2>&1", 1, True
End If

WshShell.Run "cmd /c cd /d """ & scriptDir & """ && node server.js >> logs\bridge.log 2>&1", 0, False
