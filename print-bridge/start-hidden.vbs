' Jalankan print-bridge di background tanpa jendela CMD
Set fso = CreateObject("Scripting.FileSystemObject")
Set WshShell = CreateObject("WScript.Shell")
scriptDir = fso.GetParentFolderName(WScript.ScriptFullName)

' Pastikan node_modules sudah ada
If Not fso.FolderExists(scriptDir & "\node_modules") Then
    WshShell.Run "cmd /c cd /d """ & scriptDir & """ && npm install", 1, True
End If

WshShell.Run "cmd /c cd /d """ & scriptDir & """ && node server.js", 0, False
